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

namespace spec\Sylius\Component\Resource\Metadata\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Factory\AttributesResourceMetadataFactory;
use Sylius\Component\Resource\Metadata\Factory\OperationFactory;
use Sylius\Component\Resource\Metadata\Factory\OperationFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Sylius\Component\Resource\Tests\Dummy\Resource\DummyResource;
use Sylius\Component\Resource\Tests\Dummy\Resource\DummyResourceWithCreate;
use Sylius\Component\Resource\Tests\Dummy\Resource\DummyResourceWithOperationWithoutName;

final class AttributesResourceMetadataFactorySpec extends ObjectBehavior
{
    function let(OperationFactoryInterface $operationFactory): void
    {
        $this->beConstructedWith(new OperationFactory());
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AttributesResourceMetadataFactory::class);
    }

    function it_creates_resource_metadata(): void
    {
        $metadata = $this->create(DummyResource::class);
        $metadata->shouldHaveType(ResourceMetadata::class);

        $resource = $metadata->getResource();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');
    }

    function it_creates_resource_metadata_with_operation(): void
    {
        $metadata = $this->create(DummyResourceWithCreate::class);
        $metadata->shouldHaveType(ResourceMetadata::class);

        $resource = $metadata->getResource();
        $resource->shouldHaveType(Resource::class);
        $resource->getAlias()->shouldReturn('app.dummy');
        $resource->getOperations()->shouldHaveCount(1);

        $metadata->getOperation('create')->shouldHaveType(Create::class);
    }

    function it_throws_an_exception_when_operation_has_no_name(): void
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('create', [DummyResourceWithOperationWithoutName::class]);
    }
}
