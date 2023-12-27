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

namespace spec\Sylius\Resource\Twig\Context\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Twig\Context\Factory\DefaultContextFactory;

final class DefaultContextFactorySpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(DefaultContextFactory::class);
    }

    function it_creates_twig_context_for_resource(
        \stdClass $data,
    ): void {
        $resourceMetadata = new ResourceMetadata(alias: 'app.dummy', name: 'dummy');
        $operation = (new Show())->withResource($resourceMetadata);

        $this->create($data, $operation, new Context())->shouldReturn([
            'operation' => $operation,
            'resource_metadata' => $resourceMetadata,
            'resource' => $data,
            'dummy' => $data,
        ]);
    }

    function it_creates_twig_context_for_resource_collection(
        \stdClass $data,
    ): void {
        $resourceMetadata = new ResourceMetadata(alias: 'app.dummy', pluralName: 'dummies');
        $operation = (new Index())->withResource($resourceMetadata);

        $this->create($data, $operation, new Context())->shouldReturn([
            'operation' => $operation,
            'resource_metadata' => $resourceMetadata,
            'resources' => $data,
            'dummies' => $data,
        ]);
    }
}
