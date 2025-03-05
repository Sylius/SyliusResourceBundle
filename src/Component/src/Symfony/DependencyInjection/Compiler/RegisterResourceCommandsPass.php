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

namespace Sylius\Resource\Symfony\DependencyInjection\Compiler;

use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Metadata;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Reflection\ClassReflection;
use Sylius\Resource\Symfony\Console\Operation\ConsoleOperation;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use function Symfony\Component\String\u;

final class RegisterResourceCommandsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->getDefinition('sylius.console.command.resource');

        $mapping = $container->getParameter('sylius.resource.mapping');

        /** @var string[] $paths */
        $paths = $mapping['paths'] ?? [];

        $resourcesConfig = $container->hasParameter('sylius.resources') ? $container->getParameter('sylius.resources') : [];

        foreach (ClassReflection::getResourcesByPaths($paths) as $className) {
            $alias = $this->createResourceAlias($className, $resourcesConfig);
            $this->registerCommandsForClassName($container, $definition, $className, $alias);
        }
    }

    /**
     * @param class-string $className
     */
    private function createResourceAlias(string $className, array $resourcesConfig): string
    {
        foreach ($resourcesConfig as $alias => $resourceConfig) {
            if ($className === ($resourceConfig['classes']['model'] ?? null)) {
                return $alias;
            }
        }

        return $this->getDefaultAlias($className);
    }

    /**
     * @param class-string $className
     */
    private function registerCommandsForClassName(
        ContainerBuilder $container,
        Definition $definition,
        string $className,
        ?string $alias,
    ): void {
        $resourceMetadataCollection = $this->buildResourceMetadataCollection($className, $alias);

        /** @var ResourceMetadata $resourceMetadata */
        foreach ($resourceMetadataCollection as $resourceMetadata) {
            $this->registerCommandsForClassNameAndOperations($container, $definition, $className, $resourceMetadata->getOperations() ?? new Operations());
        }
    }

    private function registerCommandsForClassNameAndOperations(
        ContainerBuilder $container,
        Definition $definition,
        string $className,
        Operations $operations,
    ): void {
        foreach ($operations as $operation) {
            if (!$operation instanceof ConsoleOperation) {
                continue;
            }

            $clonedDefinition = clone $definition;

            $clonedDefinition->addArgument($className);
            $clonedDefinition->addArgument($operation->getName());

            $container->setDefinition(sprintf('_resource.%s.%s', $className, uniqid()), $clonedDefinition);
        }
    }

    /**
     * @param class-string $className
     */
    private function buildResourceMetadataCollection(string $className, ?string $alias): ResourceMetadataCollection
    {
        $resourceMetadataCollection = new ResourceMetadataCollection();
        $operations = new Operations();

        foreach (ClassReflection::getClassAttributes($className, AsResource::class) as $resourceAttribute) {
            /** @var AsResource $resource */
            $resource = $resourceAttribute->newInstance();
            $resourceMetadata = $resource->toMetadata();

            /** @var Operation $operation */
            foreach ($resourceMetadata->getOperations() ?? new Operations() as $operation) {
                if (!$operation instanceof ConsoleOperation) {
                    continue;
                }

                if (null === $resourceMetadata->getAlias()) {
                    $resourceMetadata = $resourceMetadata->withAlias($alias);
                }

                $operation = $operation->withResource($resourceMetadata);

                $commandName = $this->createCommandName($resourceMetadata, $operation);
                $operation = $operation->withName($commandName);

                $operations->add($commandName, $operation);
            }

            $resourceMetadataCollection[] = $resourceMetadata->withOperations($operations);
        }

        return $resourceMetadataCollection;
    }

    private function createCommandName(ResourceMetadata $resourceMetadata, ConsoleOperation $operation): string
    {
        $operationName = $operation->getName();

        if (null !== $operationName) {
            return $operationName;
        }

        $metadata = Metadata::fromAliasAndConfiguration($resourceMetadata->getAlias() ?? '', []);
        $section = $resourceMetadata->getSection();

        return sprintf(
            '%s:%s-%s',
            (null !== $section ? $section . ':' : '') . $metadata->getApplicationName(),
            $operation->getShortName() ?? '',
            $metadata->getName(),
        );
    }

    /** @param class-string $className */
    private function getDefaultAlias(string $className): string
    {
        $reflectionClass = new \ReflectionClass($className);

        $shortName = $reflectionClass->getShortName();
        $suffix = 'Resource';
        if (str_ends_with($shortName, $suffix)) {
            $shortName = substr($shortName, 0, strlen($shortName) - strlen($suffix));
        }

        return 'app.' . u($shortName)->snake()->toString();
    }
}
