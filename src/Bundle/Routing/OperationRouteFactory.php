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
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation;
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
                '_sylius' => $this->getSyliusOptions($resource, $operation),
            ],
            methods: $operation->getMethods() ?? [],
        );
    }

    private function getDefaultRoutePath(MetadataInterface $metadata, Operation $operation): string
    {
        return $this->getDefaultRoutePathForOperation($metadata, $operation);
    }

    private function getDefaultRoutePathForOperation(MetadataInterface $metadata, HttpOperation $operation): string
    {
        $rootPath = sprintf('%s', Urlizer::urlize($metadata->getPluralName()));

        if (null !== $path = $operation->getPath()) {
            return $path;
        }

        $shortName = $operation->getShortName();

        if ('index' === $shortName) {
            return sprintf('%s', $rootPath);
        }

        if ($operation instanceof CreateOperationInterface) {
            $path = $shortName === 'create' ? 'new' : $shortName;

            return sprintf('%s/%s', $rootPath, $path);
        }

        if ($operation instanceof UpdateOperationInterface) {
            $path = $shortName === 'update' ? 'edit' : $shortName;

            return sprintf('%s/{id}/%s', $rootPath, $path);
        }

        if ('delete' === $shortName) {
            return sprintf('%s/{id}', $rootPath);
        }

        if ('show' === $shortName) {
            return sprintf('%s/{id}', $rootPath);
        }

        throw new \InvalidArgumentException(sprintf('Impossible to get a default route path for this operation "%s". Please define a path.', $operation::class));
    }

    private function getSyliusOptions(Resource $resource, HttpOperation $operation): array
    {
        $syliusOptions = ['resource' => $resource->getAlias()];

        if (null !== $template = $operation->getTemplate()) {
            $syliusOptions['template'] = $template;
        }

        if (null !== $section = $operation->getSection()) {
            $syliusOptions['section'] = $section;
        }

        return $syliusOptions;
    }
}
