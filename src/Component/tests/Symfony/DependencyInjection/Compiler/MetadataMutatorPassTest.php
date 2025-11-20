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

namespace Sylius\Resource\Tests\Symfony\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Sylius\Resource\Symfony\DependencyInjection\Compiler\MetadataMutatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class MetadataMutatorPassTest extends AbstractCompilerPassTestCase
{
    public function testItDoesNothingWhenResourceMutatorCollectionDoesNotExist(): void
    {
        $this->compile();

        $this->assertContainerBuilderNotHasService('sylius.metadata.mutator_collection.resource');
    }

    public function testItDoesNothingWhenOperationMutatorCollectionDoesNotExist(): void
    {
        $this->compile();

        $this->assertContainerBuilderNotHasService('sylius.metadata.mutator_collection.operation');
    }

    public function testItAddsResourceMutatorsToCollection(): void
    {
        $mutatorCollectionDefinition = new Definition();
        $this->setDefinition('sylius.metadata.mutator_collection.resource', $mutatorCollectionDefinition);

        $mutatorDefinition = new Definition();
        $mutatorDefinition->addTag('sylius.resource_mutator', ['resourceClass' => 'App\Entity\Product']);
        $this->setDefinition('app.resource_mutator.product', $mutatorDefinition);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.resource',
            'add',
            [
                'App\Entity\Product',
                new Reference('app.resource_mutator.product'),
            ],
        );
    }

    public function testItAddsMultipleResourceMutatorsToCollection(): void
    {
        $mutatorCollectionDefinition = new Definition();
        $this->setDefinition('sylius.metadata.mutator_collection.resource', $mutatorCollectionDefinition);

        $productMutatorDefinition = new Definition();
        $productMutatorDefinition->addTag('sylius.resource_mutator', ['resourceClass' => 'App\Entity\Product']);
        $this->setDefinition('app.resource_mutator.product', $productMutatorDefinition);

        $customerMutatorDefinition = new Definition();
        $customerMutatorDefinition->addTag('sylius.resource_mutator', ['resourceClass' => 'App\Entity\Customer']);
        $this->setDefinition('app.resource_mutator.customer', $customerMutatorDefinition);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.resource',
            'add',
            [
                'App\Entity\Product',
                new Reference('app.resource_mutator.product'),
            ],
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.resource',
            'add',
            [
                'App\Entity\Customer',
                new Reference('app.resource_mutator.customer'),
            ],
        );
    }

    public function testItAddsResourceMutatorWithMultipleTags(): void
    {
        $mutatorCollectionDefinition = new Definition();
        $this->setDefinition('sylius.metadata.mutator_collection.resource', $mutatorCollectionDefinition);

        $mutatorDefinition = new Definition();
        $mutatorDefinition->addTag('sylius.resource_mutator', ['resourceClass' => 'App\Entity\Product']);
        $mutatorDefinition->addTag('sylius.resource_mutator', ['resourceClass' => 'App\Entity\Customer']);
        $this->setDefinition('app.resource_mutator.multi', $mutatorDefinition);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.resource',
            'add',
            [
                'App\Entity\Product',
                new Reference('app.resource_mutator.multi'),
            ],
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.resource',
            'add',
            [
                'App\Entity\Customer',
                new Reference('app.resource_mutator.multi'),
            ],
        );
    }

    public function testItAddsOperationMutatorsToCollection(): void
    {
        $mutatorCollectionDefinition = new Definition();
        $this->setDefinition('sylius.metadata.mutator_collection.operation', $mutatorCollectionDefinition);

        $mutatorDefinition = new Definition();
        $mutatorDefinition->addTag('sylius.operation_mutator', ['operationName' => 'app_product_create']);
        $this->setDefinition('app.operation_mutator.product_create', $mutatorDefinition);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.operation',
            'add',
            [
                'app_product_create',
                new Reference('app.operation_mutator.product_create'),
            ],
        );
    }

    public function testItAddsMultipleOperationMutatorsToCollection(): void
    {
        $mutatorCollectionDefinition = new Definition();
        $this->setDefinition('sylius.metadata.mutator_collection.operation', $mutatorCollectionDefinition);

        $createMutatorDefinition = new Definition();
        $createMutatorDefinition->addTag('sylius.operation_mutator', ['operationName' => 'app_product_create']);
        $this->setDefinition('app.operation_mutator.product_create', $createMutatorDefinition);

        $updateMutatorDefinition = new Definition();
        $updateMutatorDefinition->addTag('sylius.operation_mutator', ['operationName' => 'app_product_update']);
        $this->setDefinition('app.operation_mutator.product_update', $updateMutatorDefinition);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.operation',
            'add',
            [
                'app_product_create',
                new Reference('app.operation_mutator.product_create'),
            ],
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.operation',
            'add',
            [
                'app_product_update',
                new Reference('app.operation_mutator.product_update'),
            ],
        );
    }

    public function testItAddsOperationMutatorWithMultipleTags(): void
    {
        $mutatorCollectionDefinition = new Definition();
        $this->setDefinition('sylius.metadata.mutator_collection.operation', $mutatorCollectionDefinition);

        $mutatorDefinition = new Definition();
        $mutatorDefinition->addTag('sylius.operation_mutator', ['operationName' => 'app_product_create']);
        $mutatorDefinition->addTag('sylius.operation_mutator', ['operationName' => 'app_product_update']);
        $this->setDefinition('app.operation_mutator.multi', $mutatorDefinition);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.operation',
            'add',
            [
                'app_product_create',
                new Reference('app.operation_mutator.multi'),
            ],
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.operation',
            'add',
            [
                'app_product_update',
                new Reference('app.operation_mutator.multi'),
            ],
        );
    }

    public function testItProcessesBothResourceAndOperationMutators(): void
    {
        $resourceMutatorCollectionDefinition = new Definition();
        $this->setDefinition('sylius.metadata.mutator_collection.resource', $resourceMutatorCollectionDefinition);

        $operationMutatorCollectionDefinition = new Definition();
        $this->setDefinition('sylius.metadata.mutator_collection.operation', $operationMutatorCollectionDefinition);

        $resourceMutatorDefinition = new Definition();
        $resourceMutatorDefinition->addTag('sylius.resource_mutator', ['resourceClass' => 'App\Entity\Product']);
        $this->setDefinition('app.resource_mutator', $resourceMutatorDefinition);

        $operationMutatorDefinition = new Definition();
        $operationMutatorDefinition->addTag('sylius.operation_mutator', ['operationName' => 'app_product_create']);
        $this->setDefinition('app.operation_mutator', $operationMutatorDefinition);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.resource',
            'add',
            [
                'App\Entity\Product',
                new Reference('app.resource_mutator'),
            ],
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'sylius.metadata.mutator_collection.operation',
            'add',
            [
                'app_product_create',
                new Reference('app.operation_mutator'),
            ],
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MetadataMutatorPass());
    }
}
