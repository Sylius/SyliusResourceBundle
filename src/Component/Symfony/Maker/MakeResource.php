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

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
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
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Bundle\MakerBundle\Util\ClassDetails;
use Symfony\Bundle\MakerBundle\Util\ClassSourceManipulator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

final class MakeResource extends AbstractMaker
{
    public function __construct(
        private MakerInterface $makeEntity,
        private Generator $generator,
        private FileManager $fileManager,
    ) {
    }

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
        $this->makeEntity->configureCommand($command, $inputConfig);

        $command
            ->setDescription(self::getCommandDescription())
            //->addArgument('name', InputArgument::OPTIONAL, 'Class name of the resource to create')
            ->addOption('is-entity', null, InputOption::VALUE_NONE, 'Do you want to store resource data in the database (via Doctrine)?')
        ;
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        $resourceIsEntity = $io->confirm(
            'Do you want to store resource data in the database (via Doctrine)?',
            class_exists(DoctrineBundle::class)
        );

        if (null === $input->getOption('is-entity')) {
            $input->setOption('is-entity', $resourceIsEntity);

            if ($resourceIsEntity) {
                $this->makeEntity->interact($input, $io, $command);
            }
        }
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        /** @var string $name */
        $name = $input->getArgument('name');

        $resourceIsEntity = $input->getOption('is-entity');

        if ($resourceIsEntity) {
            $this->makeEntity->generate($input, $io, $generator);
        }

        $resourceClassDetails = $generator->createClassNameDetails(
            $name,
            $resourceIsEntity ? 'Entity' : 'Sylius\\Resource',
            $resourceIsEntity ? '' : 'Resource',
        );

        $shortName = $resourceClassDetails->getShortName();

        if (\str_ends_with($shortName, 'Resource')) {
            $shortName = \mb_substr($shortName, 0, -\strlen('Resource'));
        }

        if (!class_exists($resourceClassDetails->getFullName())) {
            $generator->generateClass(
                $resourceClassDetails->getFullName(),
                __DIR__ . '/../Bundle/Resources/config/skeleton/Resource.tpl.php',
                [
                    'class_name_without_suffix' => $shortName,
                    'show_template_dir' => \strtolower($shortName),
                ],
            );

            $generator->writeChanges();

            $this->writeSuccessMessage($io);

            return;
        }

        $entityPath = $this->getPathOfClass($resourceClassDetails->getFullName());
        $manipulator = $this->createClassManipulator($entityPath, $io, true);

        $this->makeEntityAsSyliusResource($manipulator);

        $this->fileManager->dumpFile($entityPath, $manipulator->getSourceCode());

        $repoClassDetails = $this->generator->createClassNameDetails(
            $resourceClassDetails->getRelativeName(),
            'Repository\\',
            'Repository'
        );

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
