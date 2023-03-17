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

namespace Sylius\Bundle\ResourceBundle\Routing;

use Gedmo\Sluggable\Util\Urlizer;
use Sylius\Component\Resource\Action\PlaceHolderAction;
use Sylius\Component\Resource\Metadata\BulkOperationInterface;
use Sylius\Component\Resource\Metadata\CollectionOperationInterface;
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\ShowOperationInterface;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Symfony\Component\Routing\Route;

final class OperationRouteFactory implements OperationRouteFactoryInterface
{
    public function create(MetadataInterface $metadata, Resource $resource, HttpOperation $operation): Route
    {
        $routePath = $operation->getPath() ?? $this->getDefaultRoutePath($metadata, $operation);

        if (null !== $routePrefix = $operation->getRoutePrefix()) {
            $routePath = $routePrefix . '/' . $routePath;
        }

        return new Route(
            path: $routePath,
            defaults: [
                '_controller' => PlaceHolderAction::class,
                '_sylius' => $this->getSyliusOptions($resource, $operation),
            ],
            methods: $operation->getMethods() ?? [],
        );
    }

    private function getDefaultRoutePath(MetadataInterface $metadata, HttpOperation $operation): string
    {
        return $this->getDefaultRoutePathForOperation($metadata, $operation);
    }

    private function getDefaultRoutePathForOperation(MetadataInterface $metadata, HttpOperation $operation): string
    {
        $rootPath = sprintf('%s', Urlizer::urlize($metadata->getPluralName()));

        if (null !== $path = $operation->getPath()) {
            return $path;
        }

        if (null === $shortName = $operation->getShortName()) {
            throw new \InvalidArgumentException(sprintf('Operation "%s" should have a short name. Please define one.', $operation::class));
        }

        $identifier = $operation->getResource()?->getIdentifier() ?? 'id';

        if ($operation instanceof CollectionOperationInterface) {
            $path = match ($shortName) {
                'index', 'get_collection' => '',
                default => '/' . $shortName,
            };

            return sprintf('%s%s', $rootPath, $path);
        }

        if ($operation instanceof CreateOperationInterface) {
            $path = match ($shortName) {
                'create' => '/new',
                'post' => '',
                default => '/' . $shortName,
            };

            return sprintf('%s%s', $rootPath, $path);
        }

        if ($operation instanceof UpdateOperationInterface) {
            $path = match ($shortName) {
                'update' => '/edit',
                'put', 'patch' => '',
                default => '/' . $shortName,
            };

            return sprintf('%s/{%s}%s', $rootPath, $identifier, $path);
        }

        if ($operation instanceof BulkOperationInterface) {
            return sprintf('%s/%s', $rootPath, $shortName);
        }

        if ($operation instanceof DeleteOperationInterface) {
            $path = match ($shortName) {
                'delete' => '',
                default => '/' . $shortName,
            };

            return sprintf('%s/{%s}%s', $rootPath, $identifier, $path);
        }

        if ($operation instanceof ShowOperationInterface) {
            $path = match ($shortName) {
                'show', 'get' => '',
                default => '/' . $shortName,
            };

            return sprintf('%s/{%s}%s', $rootPath, $identifier, $path);
        }

        throw new \InvalidArgumentException(sprintf('Impossible to get a default route path for this operation "%s". Please define a path.', $operation::class));
    }

    private function getSyliusOptions(Resource $resource, HttpOperation $operation): array
    {
        $options = ['resource' => $resource->getAlias()];

        if (null !== $section = $resource->getSection()) {
            $options['section'] = $section;
        }

        return $options;
    }
}
