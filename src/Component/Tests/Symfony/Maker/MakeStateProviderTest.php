<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Tests\Symfony\Maker;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class MakeStateProviderTest extends MakerTestCase
{
    private const BOOK_ITEM_PROVIDER_PATH = 'tmp/Sylius/State/BookItemProvider.php';

    /** @test */
    public function it_can_create_state_providers(): void
    {
        $tester = new CommandTester((new Application(self::bootKernel()))->find('make:sylius-state-provider'));

        $this->assertFileDoesNotExist(self::file(self::BOOK_ITEM_PROVIDER_PATH));

        $tester->execute(['name' => '\\App\\Tests\\Tmp\\Sylius\\State\\BookItemProvider']);

        $this->assertFileExists(self::file(self::BOOK_ITEM_PROVIDER_PATH));
        $this->assertSame(self::getBookItemProviderExpectedContent(), \file_get_contents(self::file(self::BOOK_ITEM_PROVIDER_PATH)));
    }

    private static function getBookItemProviderExpectedContent(): string
    {
        return <<<EOF
            <?php

            namespace App\Tests\Tmp\Sylius\State;

            use Sylius\Component\Resource\Context\Context;
            use Sylius\Component\Resource\Metadata\Operation;
            use Sylius\Component\Resource\State\ProviderInterface;
            
            final class BookItemProvider implements ProviderInterface
            {
                public function provide(Operation \$operation, Context \$context): object|iterable|null
                {
                    // Retrieve the state from somewhere
            
                    return null;
                }
            }
            
            EOF
        ;
    }

    /**
     * @before
     */
    protected function removeGeneratedFiles(): void
    {
        $this->removeFile(self::file(self::BOOK_ITEM_PROVIDER_PATH));
    }
}
