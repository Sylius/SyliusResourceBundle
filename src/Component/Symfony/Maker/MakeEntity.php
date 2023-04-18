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

use PhpParser\Builder\Param;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Maker\MakeEntity as SymfonyMakeEntity;
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Bundle\MakerBundle\Util\ClassDetails;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Util\ClassSourceManipulator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * @experimental
 */
final class MakeEntity extends AbstractMaker
{
    public function __construct(
        private MakerInterface $decorated,
        private Generator $generator,
        private FileManager $fileManager,
    ) {
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

        if (!$input->getOption('sylius-resource')) {
            return;
        }

        /** @var string $name */
        $name = $input->getArgument('name');

        $entityClassDetails = $this->generator->createClassNameDetails(
            $name,
            'Entity\\',
        );

        $this->updateEntity($entityClassDetails, $io);

        $repoClassDetails = $this->generator->createClassNameDetails(
            $entityClassDetails->getRelativeName(),
            'Repository\\',
            'Repository',
        );

        $this->updateRepository($repoClassDetails, $io);
    }

    private function updateEntity(ClassNameDetails $entityClassDetails, ConsoleStyle $io): void
    {
        $entityPath = $this->getPathOfClass($entityClassDetails->getFullName());
        $manipulator = $this->createClassManipulator($entityPath, $io, true);

        $this->makeEntityAsSyliusResource($manipulator);

        $this->fileManager->dumpFile($entityPath, $manipulator->getSourceCode());
    }

    private function updateRepository(ClassNameDetails $repoClassDetails, ConsoleStyle $io): void
    {
        $repositoryPath = $this->getPathOfClass($repoClassDetails->getFullName());
        $manipulator = $this->createClassManipulator($repositoryPath, $io, true);

        $this->makeRepositoryAsSyliusRepository($manipulator);

        $this->fileManager->dumpFile($repositoryPath, $manipulator->getSourceCode());
    }

    private function makeEntityAsSyliusResource(ClassSourceManipulator $manipulator): void
    {
        $manipulator->addInterface(ResourceInterface::class);
        $manipulator->addAttributeToClass(Resource::class, []);
    }

    private function makeRepositoryAsSyliusRepository(ClassSourceManipulator $manipulator): void
    {
        $manipulator->addInterface(RepositoryInterface::class);
        $manipulator->addTrait(ResourceRepositoryTrait::class);

        $removeMethodBuilder = $manipulator->createMethodBuilder('remove', 'void', false);
        $removeMethodBuilder->addParam((new Param('resource'))->setType('ResourceInterface'));
        $manipulator->addUseStatementIfNecessary(ResourceInterface::class);

        $removeMethodBuilder->setDocComment('// TODO: You could remove this, cause this is already on the ResourceRepositoryTrait');

        $manipulator->addMethodBody(
            $removeMethodBuilder,
            <<<EOM
            <?php
            if (null !== \$this->find(\$resource->getId())) {
                \$this->_em->remove(\$resource);
                \$this->_em->flush();
            }
            EOM,
        );
        $manipulator->addMethodBuilder($removeMethodBuilder);
    }

    private function getPathOfClass(string $class): string
    {
        return (new ClassDetails($class))->getPath();
    }

    private function createClassManipulator(string $path, ConsoleStyle $io, bool $overwrite): ClassSourceManipulator
    {
        $manipulator = new ClassSourceManipulator(
            sourceCode: $this->fileManager->getFileContents($path),
            overwrite: $overwrite,
        );

        $manipulator->setIo($io);

        return $manipulator;
    }
}
