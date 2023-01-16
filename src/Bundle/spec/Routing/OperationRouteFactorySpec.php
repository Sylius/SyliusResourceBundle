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

namespace spec\Sylius\Bundle\ResourceBundle\Routing;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Routing\OperationRouteFactory;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Metadata\Update;

final class OperationRouteFactorySpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(OperationRouteFactory::class);
    }

    function it_generates_create_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Create(),
        );

        $route->getPath()->shouldReturn('/dummies/new');
        $route->getMethods()->shouldReturn(['GET', 'POST']);
        $route->getDefaults()->shouldReturn([
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_index_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Index(),
        );

        $route->getPath()->shouldReturn('/dummies');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_show_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Show(),
        );

        $route->getPath()->shouldReturn('/dummies/{id}');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_update_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Update(),
        );

        $route->getPath()->shouldReturn('/dummies/{id}/edit');
        $route->getMethods()->shouldReturn(['GET', 'PUT']);
        $route->getDefaults()->shouldReturn([
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_delete_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Delete(),
        );

        $route->getPath()->shouldReturn('/dummies/{id}');
        $route->getMethods()->shouldReturn(['DELETE']);
        $route->getDefaults()->shouldReturn([
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_custom_operations_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new HttpOperation(methods: ['PATCH'], path: 'dummies/{id}/custom'),
        );

        $route->getPath()->shouldReturn('/dummies/{id}/custom');
        $route->getMethods()->shouldReturn(['PATCH']);
        $route->getDefaults()->shouldReturn([
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_throws_an_exception_when_operation_does_not_have_path(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->shouldThrow(new \InvalidArgumentException(sprintf('Impossible to get a default route path for this operation "%s". Please define a path.', HttpOperation::class)))->during(
            'create',
            [
                $metadata,
                new Resource('app.dummy'),
                new HttpOperation(),
            ],
        );
    }
}
