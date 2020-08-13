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

namespace spec\Sylius\Bundle\ResourceBundle\Controller;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class RequestConfigurationFactorySpec extends ObjectBehavior
{
    function let(ParametersParserInterface $parametersParser): void
    {
        $this->beConstructedWith($parametersParser, RequestConfiguration::class);
    }

    function it_implements_request_configuration_factory_interface(): void
    {
        $this->shouldImplement(RequestConfigurationFactoryInterface::class);
    }

    function it_creates_configuration_from_resource_metadata_and_request(
        ParametersParserInterface $parametersParser,
        MetadataInterface $metadata,
        Request $request,
        HeaderBag $headersBag,
        ParameterBag $attributesBag
    ): void {
        $request->headers = $headersBag;
        $request->attributes = $attributesBag;

        $headersBag->all('Accept')->willReturn([]);

        $attributesBag->get('_sylius', [])->willReturn(['template' => ':Product:show.html.twig']);
        $parametersParser
            ->parseRequestValues(['template' => ':Product:show.html.twig'], $request)
            ->willReturn(['template' => ':Product:list.html.twig'])
        ;

        $this->create($metadata, $request)->shouldHaveType(RequestConfiguration::class);
    }

    function it_creates_configuration_without_default_settings(
        ParametersParserInterface $parametersParser,
        MetadataInterface $metadata,
        Request $request,
        HeaderBag $headersBag,
        ParameterBag $attributesBag
    ): void {
        $request->headers = $headersBag;
        $request->attributes = $attributesBag;

        $headersBag->all('Accept')->willReturn([]);

        $attributesBag->get('_sylius', [])->willReturn(['template' => ':Product:list.html.twig']);
        $parametersParser
            ->parseRequestValues(['template' => ':Product:list.html.twig'], $request)
            ->willReturn(['template' => ':Product:list.html.twig'])
        ;

        $this->create($metadata, $request)->isSortable()->shouldReturn(false);
    }

    function it_creates_configuration_for_serialization_group_from_single_header(
        ParametersParserInterface $parametersParser,
        MetadataInterface $metadata,
        Request $request,
        HeaderBag $headersBag,
        ParameterBag $attributesBag
    ): void {
        $request->headers = $headersBag;
        $request->attributes = $attributesBag;

        $attributesBag->get('_sylius', [])->willReturn([
            'allowed_serialization_groups' => ['Default', 'Detailed', 'Other'],
        ]);
        $headersBag->all('Accept')->willReturn(['groups=Default,Detailed']);

        $parametersParser
            ->parseRequestValues(
                [
                    'allowed_serialization_groups' => ['Default', 'Detailed', 'Other'],
                    'serialization_groups' => ['Default', 'Detailed'],
                ],
                $request
            )
            ->willReturn(['serialization_groups' => ['Default', 'Detailed']])
        ;

        $this->create($metadata, $request)->getSerializationGroups()->shouldReturn(['Default', 'Detailed']);
    }

    function it_creates_configuration_for_serialization_group_from_multiple_headers(
        ParametersParserInterface $parametersParser,
        MetadataInterface $metadata,
        Request $request,
        HeaderBag $headersBag,
        ParameterBag $attributesBag
    ): void {
        $request->headers = $headersBag;
        $request->attributes = $attributesBag;

        $attributesBag->get('_sylius', [])->willReturn([
            'allowed_serialization_groups' => ['Default', 'Detailed', 'Other'],
        ]);
        $headersBag->all('Accept')->willReturn(['application/json', 'groups=Default,Detailed']);

        $parametersParser
            ->parseRequestValues(
                [
                    'allowed_serialization_groups' => ['Default', 'Detailed', 'Other'],
                    'serialization_groups' => ['Default', 'Detailed'],
                ],
                $request
            )
            ->willReturn(['serialization_groups' => ['Default', 'Detailed']])
        ;

        $this->create($metadata, $request)->getSerializationGroups()->shouldReturn(['Default', 'Detailed']);
    }

    function it_creates_configuration_using_only_those_serialization_groups_that_are_allowed(
        ParametersParserInterface $parametersParser,
        MetadataInterface $metadata,
        Request $request,
        ParameterBag $headersBag,
        ParameterBag $attributesBag
    ): void {
        $request->headers = $headersBag;
        $request->attributes = $attributesBag;

        $attributesBag->get('_sylius', [])->willReturn([
            'allowed_serialization_groups' => ['Default'],
        ]);
        $headersBag->all('Accept')->willReturn(['application/json', 'groups=Default,Detailed']);

        $parametersParser
            ->parseRequestValues(
                [
                    'allowed_serialization_groups' => ['Default'],
                    'serialization_groups' => ['Default'],
                ],
                $request
            )
            ->willReturn(['serialization_groups' => ['Default']])
        ;

        $this->create($metadata, $request)->getSerializationGroups()->shouldReturn(['Default']);
    }

    function it_creates_configuration_using_only_those_serialization_groups_that_are_allowed_or_defined_as_default(
        ParametersParserInterface $parametersParser,
        MetadataInterface $metadata,
        Request $request,
        ParameterBag $headersBag,
        ParameterBag $attributesBag
    ): void {
        $request->headers = $headersBag;
        $request->attributes = $attributesBag;

        $attributesBag->get('_sylius', [])->willReturn([
            'allowed_serialization_groups' => ['Default'],
            'serialization_groups' => ['Detailed'],
        ]);
        $headersBag->all('Accept')->willReturn(['application/json', 'groups=Default,Detailed,Other']);

        $parametersParser
            ->parseRequestValues(
                [
                    'allowed_serialization_groups' => ['Default'],
                    'serialization_groups' => ['Default', 'Detailed'],
                ],
                $request
            )
            ->willReturn(['serialization_groups' => ['Default', 'Detailed']])
        ;

        $this->create($metadata, $request)->getSerializationGroups()->shouldReturn(['Default', 'Detailed']);
    }

    function it_creates_configuration_using_only_those_serialization_groups_that_are_defined_as_default(
        ParametersParserInterface $parametersParser,
        MetadataInterface $metadata,
        Request $request,
        ParameterBag $headersBag,
        ParameterBag $attributesBag
    ): void {
        $request->headers = $headersBag;
        $request->attributes = $attributesBag;

        $attributesBag->get('_sylius', [])->willReturn([
            'serialization_groups' => ['Detailed'],
        ]);
        $headersBag->all('Accept')->willReturn(['application/json', 'groups=Default,Detailed,Other']);

        $parametersParser
            ->parseRequestValues(['serialization_groups' => ['Detailed']], $request)
            ->willReturn(['serialization_groups' => ['Detailed']])
        ;

        $this->create($metadata, $request)->getSerializationGroups()->shouldReturn(['Detailed']);
    }

    function it_creates_configuration_for_serialization_version_from_single_header(
        ParametersParserInterface $parametersParser,
        MetadataInterface $metadata,
        Request $request,
        HeaderBag $headersBag,
        ParameterBag $attributesBag
    ): void {
        $request->headers = $headersBag;
        $request->attributes = $attributesBag;

        $headersBag->all('Accept')->willReturn(['version=1.0.0']);

        $attributesBag->get('_sylius', [])->willReturn([]);

        $parametersParser
            ->parseRequestValues(['serialization_version' => '1.0.0'], $request)
            ->willReturn(['template' => ':Product:list.html.twig'])
        ;

        $this->create($metadata, $request)->isSortable()->shouldReturn(false);
    }

    function it_creates_configuration_for_serialization_version_from_multiple_headers(
        ParametersParserInterface $parametersParser,
        MetadataInterface $metadata,
        Request $request,
        HeaderBag $headersBag,
        ParameterBag $attributesBag
    ): void {
        $request->headers = $headersBag;
        $request->attributes = $attributesBag;

        $headersBag->all('Accept')->willReturn(['application/xml', 'version=1.0.0']);

        $attributesBag->get('_sylius', [])->willReturn([]);

        $parametersParser
            ->parseRequestValues(['serialization_version' => '1.0.0'], $request)
            ->willReturn(['template' => ':Product:list.html.twig'])
        ;

        $this->create($metadata, $request)->isSortable()->shouldReturn(false);
    }

    function it_creates_configuration_with_default_settings(
        ParametersParserInterface $parametersParser,
        MetadataInterface $metadata,
        Request $request,
        HeaderBag $headersBag,
        ParameterBag $attributesBag
    ): void {
        $this->beConstructedWith($parametersParser, RequestConfiguration::class, ['sortable' => true]);

        $request->headers = $headersBag;
        $request->attributes = $attributesBag;

        $headersBag->all('Accept')->willReturn([]);

        $attributesBag->get('_sylius', [])->willReturn(['template' => ':Product:list.html.twig']);

        $parametersParser
            ->parseRequestValues(['template' => ':Product:list.html.twig', 'sortable' => true], $request)
            ->willReturn(['template' => ':Product:list.html.twig', 'sortable' => true])
        ;

        $this->create($metadata, $request)->isSortable()->shouldReturn(true);
    }
}
