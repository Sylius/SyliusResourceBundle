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

namespace Sylius\Resource\Tests\Twig\Context\Factory;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Twig\Context\Factory\DefaultContextFactory;

final class DefaultContextFactoryTest extends TestCase
{
    private DefaultContextFactory $defaultContextFactory;

    protected function setUp(): void
    {
        $this->defaultContextFactory = new DefaultContextFactory();
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(DefaultContextFactory::class, $this->defaultContextFactory);
    }

    public function testItCreatesTwigContextForResource(): void
    {
        $data = new \stdClass();
        $resourceMetadata = new ResourceMetadata(alias: 'app.dummy', name: 'dummy');
        $operation = (new Show())->withResource($resourceMetadata);
        $context = new Context();

        $result = $this->defaultContextFactory->create($data, $operation, $context);

        $this->assertSame([
            'operation' => $operation,
            'resource_metadata' => $resourceMetadata,
            'resource' => $data,
            'dummy' => $data,
        ], $result);
    }

    public function testItCreatesTwigContextForResourceCollection(): void
    {
        $data = new \stdClass();
        $resourceMetadata = new ResourceMetadata(alias: 'app.dummy', pluralName: 'dummies');
        $operation = (new Index())->withResource($resourceMetadata);
        $context = new Context();

        $result = $this->defaultContextFactory->create($data, $operation, $context);

        $this->assertSame([
            'operation' => $operation,
            'resource_metadata' => $resourceMetadata,
            'resources' => $data,
            'dummies' => $data,
        ], $result);
    }
}
