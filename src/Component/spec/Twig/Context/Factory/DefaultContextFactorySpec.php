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

namespace spec\Sylius\Component\Resource\Twig\Context\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Twig\Context\Factory\DefaultContextFactory;

final class DefaultContextFactorySpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(DefaultContextFactory::class);
    }

    function it_creates_twig_context_for_resource(
        \stdClass $data,
    ): void {
        $operation = (new Show())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy'));

        $this->create($data, $operation, new Context())->shouldReturn([
            'operation' => $operation,
            'resource' => $data,
            'dummy' => $data,
        ]);
    }

    function it_creates_twig_context_for_resource_collection(
        \stdClass $data,
    ): void {
        $operation = (new Index())->withResource(new ResourceMetadata(alias: 'app.dummy', pluralName: 'dummies'));

        $this->create($data, $operation, new Context())->shouldReturn([
            'operation' => $operation,
            'resources' => $data,
            'dummies' => $data,
        ]);
    }
}
