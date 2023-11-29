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

namespace Sylius\Component\Resource\Symfony\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

final class MakeStateProvider extends AbstractMaker
{
    /**
     * @inheritdoc
     */
    public static function getCommandName(): string
    {
        return 'make:sylius-state-provider';
    }

    /**
     * @inheritdoc
     */
    public static function getCommandDescription(): string
    {
        return 'Creates a Sylius state provider';
    }

    /**
     * @inheritdoc
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument('name', InputArgument::REQUIRED, 'Choose a class name for your state provider (e.g. <fg=yellow>AwesomeStateProvider</>)')
            ->setHelp(file_get_contents(__DIR__ . '/Resources/help/MakeStateProvider.txt'))
        ;
    }

    /**
     * @inheritdoc
     */
    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }

    /**
     * @inheritdoc
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        /** @var string $name */
        $name = $input->getArgument('name');

        $stateProviderClassNameDetails = $generator->createClassNameDetails(
            $name,
            'State\\',
        );

        $generator->generateClass(
            $stateProviderClassNameDetails->getFullName(),
            __DIR__ . '/Resources/skeleton/StateProvider.tpl.php',
        );
        $generator->writeChanges();

        $this->writeSuccessMessage($io);
        $io->text([
            'Next: Open your new state provider class and start customizing it.',
        ]);
    }
}
