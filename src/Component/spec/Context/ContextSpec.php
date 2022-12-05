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

namespace spec\Sylius\Component\Resource\Context;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Controller\Parameters;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\Request;

final class ContextSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Context::class);
    }

    function it_is_an_iterator(): void
    {
        $this->shouldImplement(\IteratorAggregate::class);
    }

    function it_can_be_constructed_with_options(MetadataInterface $metadata): void
    {
        $request = new Request();
        $requestConfiguration = new RequestConfiguration($metadata->getWrappedObject(), $request, new Parameters([]));

        $this->beConstructedWith($requestConfiguration, $request);

        $this->get(RequestConfiguration::class)->shouldReturn($requestConfiguration);
        $this->get(Request::class)->shouldReturn($request);
    }

    function it_can_be_with_options(MetadataInterface $metadata): void
    {
        $request = new Request();
        $requestConfiguration = new RequestConfiguration($metadata->getWrappedObject(), $request, new Parameters([]));

        $self = $this->with($requestConfiguration, $request);

        $self->get(RequestConfiguration::class)->shouldReturn($requestConfiguration);
        $self->get(Request::class)->shouldReturn($request);
    }

    function it_can_be_without_options(MetadataInterface $metadata): void
    {
        $request = new Request();
        $requestConfiguration = new RequestConfiguration($metadata->getWrappedObject(), $request, new Parameters([]));

        $self = $this->with($requestConfiguration, $request);
        $self = $self->without(RequestConfiguration::class, Request::class);

        $self->get(RequestConfiguration::class)->shouldReturn(null);
        $self->get(Request::class)->shouldReturn(null);
    }

    function it_can_be_iterated(MetadataInterface $metadata): void
    {
        $request = new Request();
        $requestConfiguration = new RequestConfiguration($metadata->getWrappedObject(), $request, new Parameters([]));

        $this->beConstructedWith($requestConfiguration, $request);

        $this->getIterator()->shouldHaveKey(0);
        $this->getIterator()->shouldHaveKey(1);

        $this->getIterator()->current()->shouldReturn($requestConfiguration);
    }
}
