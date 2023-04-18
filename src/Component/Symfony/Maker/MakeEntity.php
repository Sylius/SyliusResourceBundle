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

namespace Sylius\Component\Resource\Symfony\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Maker\MakeEntity as SymfonyMakeEntity;
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

final class MakeEntity extends AbstractMaker
{
    public function __construct(private MakerInterface $decorated)
    {
    }

    public static function getCommandName(): string
    {
        return SymfonyMakeEntity::getCommandName();
    }

    public static function getCommandDescription(): string
    {
        return SymfonyMakeEntity::getCommandDescription();
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $this->decorated->configureCommand($command, $inputConfig);

        $command->addOption('sylius-resource', 'r', InputOption::VALUE_NONE, 'Mark this class as a Sylius resource');
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        $this->decorated->configureDependencies($dependencies);
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        $this->decorated->interact($input, $io, $command);
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $this->decorated->generate($input, $io, $generator);
    }
}
