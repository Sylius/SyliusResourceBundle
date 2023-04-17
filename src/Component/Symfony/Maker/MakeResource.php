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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

final class MakeResource extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:resource';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a Resource class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription(self::getCommandDescription())
            ->addArgument('class', InputArgument::OPTIONAL, 'Class name of the resource to create')
            ->addOption('namespace', null, InputOption::VALUE_REQUIRED, 'Customize the namespace for generated resource', 'Sylius\\Resource')
        ;
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $class = $input->getArgument('class');

        $namespace = $input->getOption('namespace');
        $namespace = \trim($namespace, '\\');

        $resourceClassDetails = $generator->createClassNameDetails(
            $class,
            $namespace,
            'Resource',
        );

        if (\str_ends_with($class, 'Resource')) {
            $class = \mb_substr($class, 0, -\strlen('Resource'));
        }

        $generator->generateClass(
            $resourceClassDetails->getFullName(),
            __DIR__ . '/../Bundle/Resources/config/skeleton/Resource.tpl.php',
            [
                'class_name_without_suffix' => $class,
                'show_template_dir' => \strtolower($class),
            ],
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
    }
}
