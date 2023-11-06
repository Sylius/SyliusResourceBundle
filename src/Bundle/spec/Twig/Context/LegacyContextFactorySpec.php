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

namespace spec\Sylius\Bundle\ResourceBundle\Twig\Context;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Twig\Context\LegacyContextFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\MetadataOption;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;

final class LegacyContextFactorySpec extends ObjectBehavior
{
    function let(ContextFactoryInterface $decorated): void
    {
        $this->beConstructedWith($decorated);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(LegacyContextFactory::class);
    }

    function it_adds_twig_vars(
        ContextFactoryInterface $decorated,
        \stdClass $data,
        Operation $operation,
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
    ): void {
        $context = new Context(
            new RequestConfigurationOption($requestConfiguration->getWrappedObject()),
            new MetadataOption($metadata->getWrappedObject()),
        );

        $decorated->create($data, $operation, $context)->willReturn(['resource' => $data]);

        $this->create($data, $operation, $context)->shouldReturn([
            'resource' => $data,
            'configuration' => $requestConfiguration,
            'metadata' => $metadata,
        ]);
    }

    function it_does_not_add_twig_vars_if_is_not_necessary(
        ContextFactoryInterface $decorated,
        \stdClass $data,
        Operation $operation,
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
    ): void {
        $context = new Context();

        $decorated->create($data, $operation, $context)->willReturn(['resource' => $data]);

        $this->create($data, $operation, $context)->shouldReturn([
            'resource' => $data,
        ]);
    }
}
