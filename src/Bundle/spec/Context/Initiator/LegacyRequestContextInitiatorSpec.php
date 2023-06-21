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

namespace spec\Sylius\Bundle\ResourceBundle\Context\Initiator;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Context\Initiator\LegacyRequestContextInitiator;
use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Component\Resource\Context\Option\MetadataOption;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class LegacyRequestContextInitiatorSpec extends ObjectBehavior
{
    function let(
        RegistryInterface $resourceRegistry,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        RequestContextInitiatorInterface $decorated,
    ): void {
        $this->beConstructedWith($resourceRegistry, $requestConfigurationFactory, $decorated);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(LegacyRequestContextInitiator::class);
    }

    function it_adds_metadata_and_request_configuration_to_the_context(
        Request $request,
        RequestContextInitiatorInterface $decorated,
        RegistryInterface $resourceRegistry,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        MetadataInterface $metadata,
        RequestConfiguration $requestConfiguration,
    ): void {
        $request->attributes = new ParameterBag(['_sylius' => ['resource' => 'app.dummy']]);

        $decorated->initializeContext($request)->willReturn(new Context());

        $resourceRegistry->get('app.dummy')->willReturn($metadata)->shouldBeCalled();

        $requestConfigurationFactory->create($metadata, $request)->willReturn($requestConfiguration)->shouldBeCalled();

        $result = $this->initializeContext($request);
        $result->shouldHaveType(Context::class);

        $result->get(MetadataOption::class)?->metadata()->shouldReturn($metadata);
        $result->get(RequestConfigurationOption::class)?->requestConfiguration()->shouldReturn($requestConfiguration);
    }

    function it_directly_returns_the_context_when_request_has_no_sylius_attributes(
        Request $request,
        RequestContextInitiatorInterface $decorated,
        RegistryInterface $resourceRegistry,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        MetadataInterface $metadata,
        RequestConfiguration $requestConfiguration,
    ): void {
        $request->attributes = new ParameterBag();

        $decorated->initializeContext($request)->willReturn(new Context());

        $resourceRegistry->get('app.dummy')->willReturn($metadata)->shouldNotBeCalled();

        $requestConfigurationFactory->create($metadata, $request)->willReturn($requestConfiguration)->shouldNotBeCalled();

        $result = $this->initializeContext($request);
        $result->shouldHaveType(Context::class);

        $result->get(MetadataOption::class)->shouldReturn(null);
        $result->get(RequestConfigurationOption::class)->shouldReturn(null);
    }

    function it_directly_returns_the_context_when_request_has_no_resource_on_attributes(
        Request $request,
        RequestContextInitiatorInterface $decorated,
        RegistryInterface $resourceRegistry,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        MetadataInterface $metadata,
        RequestConfiguration $requestConfiguration,
    ): void {
        $request->attributes = new ParameterBag(['_sylius' => ['section' => 'admin']]);

        $decorated->initializeContext($request)->willReturn(new Context());

        $resourceRegistry->get('app.dummy')->willReturn($metadata)->shouldNotBeCalled();

        $requestConfigurationFactory->create($metadata, $request)->willReturn($requestConfiguration)->shouldNotBeCalled();

        $result = $this->initializeContext($request);
        $result->shouldHaveType(Context::class);

        $result->get(MetadataOption::class)->shouldReturn(null);
        $result->get(RequestConfigurationOption::class)->shouldReturn(null);
    }
}
