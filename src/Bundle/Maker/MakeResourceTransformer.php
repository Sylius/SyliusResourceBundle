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

namespace Sylius\Bundle\ResourceBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

final class MakeResourceTransformer extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:resource-transformer';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a Resource transformer to convert a resource to another one.';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription(self::getCommandDescription())
            ->addArgument('from', InputArgument::REQUIRED, 'From resource class to create a transformer for')
            ->addArgument('to', InputArgument::REQUIRED, 'To resource class to create a transformer for')
            ->addOption('namespace', null, InputOption::VALUE_REQUIRED, 'Customize the namespace for generated transformers', 'DataTransformer')
        ;

        $inputConfig->setArgumentAsNonInteractive('from');
        $inputConfig->setArgumentAsNonInteractive('to');
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        // No dependencies needed
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $from = $input->getArgument('from');
        $to = $input->getArgument('to');

        if (!\class_exists($from)) {
            throw new RuntimeCommandException(\sprintf('From resource class "%s" not found.', $input->getArgument('from')));
        }

        if (!\class_exists($to)) {
            throw new RuntimeCommandException(\sprintf('To resource class "%s" not found.', $input->getArgument('to')));
        }

        $namespace = $input->getOption('namespace');

        // strip maker's root namespace if set
        if (0 === \mb_strpos($namespace, $generator->getRootNamespace())) {
            $namespace = \mb_substr($namespace, \mb_strlen($generator->getRootNamespace()));
        }

        $namespace = \trim($namespace, '\\');

        $from = new \ReflectionClass($from);
        $to = new \ReflectionClass($to);

        $fromShortName = $this->getShortName($from);
        $toShortName = $this->getShortName($to);
        $transformerName = $fromShortName . 'To' . $toShortName;
        $transformer = $generator->createClassNameDetails($transformerName, $namespace, 'Transformer');

        $generator->generateClass(
            $transformer->getFullName(),
            __DIR__ . '/../Resources/config/skeleton/ResourceTransformer.tpl.php',
            [
                'from' => $from,
                'fromShortName' => $fromShortName,
                'to' => $to,
                'toShortName' => $toShortName,
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
    }

    private function getShortName(\ReflectionClass $class): string
    {
        $namespaces = explode('\\', $class->getNamespaceName());
        $suffix = end($namespaces);

        return $class->getShortName() . $suffix;
    }
}
