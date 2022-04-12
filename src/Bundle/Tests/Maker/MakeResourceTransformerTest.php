<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Tests\Maker;

use App\Dto\User as UserDto;
use App\Entity\User as UserEntity;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class MakeResourceTransformerTest extends MakerTestCase
{
    private const USER_ENTITY_TO_DTO_TRANSFORMER_PATH = 'DataTransformer/UserEntityToUserDtoTransformer.php';

    /** @test */
    public function it_can_create_resource_transformers(): void
    {
        $tester = new CommandTester((new Application(self::bootKernel()))->find('make:resource-transformer'));

        $this->assertFileDoesNotExist(self::tempFile(self::USER_ENTITY_TO_DTO_TRANSFORMER_PATH));

        $tester->execute(['from' => UserEntity::class, 'to' => UserDto::class, '--namespace' => 'Tests\Tmp\DataTransformer']);

        $this->assertFileExists(self::tempFile(self::USER_ENTITY_TO_DTO_TRANSFORMER_PATH));
        $this->assertSame(self::getBookEntityToDtoTransformerExpectedContent(), \file_get_contents(self::tempFile(self::USER_ENTITY_TO_DTO_TRANSFORMER_PATH)));
    }

    private static function getBookEntityToDtoTransformerExpectedContent(): string
    {
        return <<<EOF
<?php

namespace App\Tests\Tmp\DataTransformer;

use App\Entity\User as UserEntity;
use App\Dto\User as UserDto;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\DataTransformer\DataTransformerInterface;
use Webmozart\Assert\Assert;

final class UserEntityToUserDtoTransformer implements DataTransformerInterface
{
    public function transform(object \$data, string \$to, RequestConfiguration \$configuration): UserDto
    {
        Assert::isInstanceOf(\$data, UserEntity::class);

        // TODO implement your logic
        return new UserDto();
    }

    public function supportsTransformation(object \$data, string \$to, RequestConfiguration \$configuration): bool
    {
        return \$data instanceof UserEntity && is_a(\$to, UserDto::class, true);
    }
}

EOF;
    }
}
