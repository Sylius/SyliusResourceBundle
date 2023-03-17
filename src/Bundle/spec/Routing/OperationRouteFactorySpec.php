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
use Sylius\Component\Resource\Action\PlaceHolderAction;
use Sylius\Component\Resource\Metadata\Api;
use Sylius\Component\Resource\Metadata\BulkDelete;
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
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_create_routes_with_custom_short_name(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Create(shortName: 'register'),
        );

        $route->getPath()->shouldReturn('/dummies/register');
        $route->getMethods()->shouldReturn(['GET', 'POST']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_api_post_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Api\Post(),
        );

        $route->getPath()->shouldReturn('/dummies');
        $route->getMethods()->shouldReturn(['POST']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
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
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_index_routes_with_custom_short_name(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Index(shortName: 'list'),
        );

        $route->getPath()->shouldReturn('/dummies/list');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_api_get_collection_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Api\GetCollection(),
        );

        $route->getPath()->shouldReturn('/dummies');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
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
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_show_routes_with_custom_short_name(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Show(shortName: 'details'),
        );

        $route->getPath()->shouldReturn('/dummies/{id}/details');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_api_get_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Api\Get(),
        );

        $route->getPath()->shouldReturn('/dummies/{id}');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_show_routes_with_custom_identifier(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $resource = new Resource('app.dummy', identifier: 'code');

        /** @var HttpOperation $operation */
        $operation = (new Show())->withResource($resource);

        $route = $this->create(
            $metadata,
            $resource,
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/{code}');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
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
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_update_routes_with_custom_identifier(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $resource = new Resource('app.dummy', identifier: 'code');

        /** @var HttpOperation $operation */
        $operation = (new Update())->withResource($resource);

        $route = $this->create(
            $metadata,
            $resource,
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/{code}/edit');
        $route->getMethods()->shouldReturn(['GET', 'PUT']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_update_routes_with_custom_short_name(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Update(shortName: 'edition'),
        );

        $route->getPath()->shouldReturn('/dummies/{id}/edition');
        $route->getMethods()->shouldReturn(['GET', 'PUT']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_api_put_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Api\Put(),
        );

        $route->getPath()->shouldReturn('/dummies/{id}');
        $route->getMethods()->shouldReturn(['PUT']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_api_patch_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Api\Patch(),
        );

        $route->getPath()->shouldReturn('/dummies/{id}');
        $route->getMethods()->shouldReturn(['PATCH']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
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
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_delete_routes_with_custom_short_name(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Delete(shortName: 'remove'),
        );

        $route->getPath()->shouldReturn('/dummies/{id}/remove');
        $route->getMethods()->shouldReturn(['DELETE']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_api_delete_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new Api\Delete(),
        );

        $route->getPath()->shouldReturn('/dummies/{id}');
        $route->getMethods()->shouldReturn(['DELETE']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_delete_routes_with_custom_identifier(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $resource = new Resource('app.dummy', identifier: 'code');

        /** @var HttpOperation $operation */
        $operation = (new Delete())->withResource($resource);

        $route = $this->create(
            $metadata,
            $resource,
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/{code}');
        $route->getMethods()->shouldReturn(['DELETE']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_bulk_delete_routes(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource('app.dummy'),
            new BulkDelete(),
        );

        $route->getPath()->shouldReturn('/dummies/bulk_delete');
        $route->getMethods()->shouldReturn(['DELETE']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
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
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_routes_with_sections(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $route = $this->create(
            $metadata,
            new Resource(alias: 'app.dummy', section: 'admin'),
            new Show(),
        );

        $route->getPath()->shouldReturn('/dummies/{id}');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
                'section' => 'admin',
            ],
        ]);
    }

    function it_throws_an_exception_when_operation_does_not_have_short_name(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->shouldThrow(new \InvalidArgumentException(sprintf('Operation "%s" should have a short name. Please define one.', HttpOperation::class)))->during(
            'create',
            [
                $metadata,
                new Resource('app.dummy'),
                new HttpOperation(),
            ],
        );
    }

    function it_throws_an_exception_when_operation_does_not_have_path(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->shouldThrow(new \InvalidArgumentException(sprintf('Impossible to get a default route path for this operation "%s". Please define a path.', HttpOperation::class)))->during(
            'create',
            [
                $metadata,
                new Resource('app.dummy'),
                new HttpOperation(shortName: 'custom'),
            ],
        );
    }
}
