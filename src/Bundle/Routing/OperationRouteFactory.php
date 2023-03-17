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
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Resource;
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

        if ('index' === $shortName) {
            return sprintf('%s', $rootPath);
        }

        if ($operation instanceof CreateOperationInterface) {
            $path = $shortName === 'create' ? 'new' : $shortName;

            return sprintf('%s/%s', $rootPath, $path);
        }

        if ($operation instanceof UpdateOperationInterface) {
            $path = $shortName === 'update' ? 'edit' : $shortName;

            return sprintf('%s/{%s}/%s', $rootPath, $identifier, $path);
        }

        if ('delete' === $shortName) {
            return sprintf('%s/{%s}', $rootPath, $identifier);
        }

        if ('bulk_delete' === $shortName) {
            return sprintf('%s/bulk_delete', $rootPath);
        }

        if ('show' === $shortName) {
            return sprintf('%s/{%s}', $rootPath, $identifier);
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
