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

namespace Sylius\Component\Resource\Tests\Symfony\Maker;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class MakeStateProcessorTest extends MakerTestCase
{
    private const CREATE_BOOK_PROCESSOR_PATH = 'tmp/Sylius/State/CreateBookProcessor.php';

    /** @test */
    public function it_can_create_state_processors(): void
    {
        $tester = new CommandTester((new Application(self::bootKernel()))->find('make:sylius-state-processor'));

        $this->assertFileDoesNotExist(self::file(self::CREATE_BOOK_PROCESSOR_PATH));

        $tester->execute(['name' => '\\App\\Tests\\Tmp\\Sylius\\State\\CreateBookProcessor']);

        $this->assertFileExists(self::file(self::CREATE_BOOK_PROCESSOR_PATH));
        $this->assertSame(self::getCreateBookProcessorExpectedContent(), \file_get_contents(self::file(self::CREATE_BOOK_PROCESSOR_PATH)));
    }

    private static function getCreateBookProcessorExpectedContent(): string
    {
        return <<<EOF
            <?php

            namespace App\Tests\Tmp\Sylius\State;

            use Sylius\Component\Resource\Context\Context;
            use Sylius\Component\Resource\Metadata\Operation;
            use Sylius\Component\Resource\State\ProcessorInterface;
            
            final class CreateBookProcessor implements ProcessorInterface
            {
                public function process(mixed \$data, Operation \$operation, Context \$context): mixed
                {
                    // Handle the state
            
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
        $this->removeFile(self::file(self::CREATE_BOOK_PROCESSOR_PATH));
    }
}
