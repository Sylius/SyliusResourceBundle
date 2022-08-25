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
use Sylius\Component\Resource\Annotation\CreateAction;
use Sylius\Component\Resource\Annotation\DeleteAction;
use Sylius\Component\Resource\Annotation\IndexAction;
use Sylius\Component\Resource\Annotation\ShowAction;
use Sylius\Component\Resource\Annotation\SyliusRoute;
use Sylius\Component\Resource\Annotation\UpdateAction;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Reflection\ClassReflection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;

final class RouteAttributesFactory implements RouteAttributesFactoryInterface
{
    public function __construct(private RegistryInterface $resourceRegistry)
    {
    }

    public function createRouteForClass(RouteCollection $routeCollection, string $className): void
    {
        $this->createRouteForAttributes($routeCollection, ClassReflection::getClassAttributes($className, SyliusRoute::class));
        $this->createRouteForAttributes($routeCollection, ClassReflection::getClassAttributes($className, CreateAction::class), 'create', ['GET', 'POST']);
        $this->createRouteForAttributes($routeCollection, ClassReflection::getClassAttributes($className, IndexAction::class), 'index', ['GET']);
        $this->createRouteForAttributes($routeCollection, ClassReflection::getClassAttributes($className, UpdateAction::class), 'update', ['GET', 'PUT']);
        $this->createRouteForAttributes($routeCollection, ClassReflection::getClassAttributes($className, ShowAction::class), 'show', ['GET']);
        $this->createRouteForAttributes($routeCollection, ClassReflection::getClassAttributes($className, DeleteAction::class), 'delete', ['DELETE']);
    }

    /** @param \ReflectionAttribute[] $attributes */
    private function createRouteForAttributes(RouteCollection $routeCollection, array $attributes, ?string $operation = null, array $methods = []): void
    {
        foreach ($attributes as $reflectionAttribute) {
            $arguments = $reflectionAttribute->getArguments();

            $syliusOptions = [];

            if (isset($arguments['template'])) {
                $syliusOptions['template'] = $arguments['template'];
            }

            if (isset($arguments['vars'])) {
                $syliusOptions['vars'] = $arguments['vars'];
            }

            if (isset($arguments['criteria'])) {
                $syliusOptions['criteria'] = $arguments['criteria'];
            }

            if (isset($arguments['repository'])) {
                $syliusOptions['repository'] = $arguments['repository'];
            }

            if (isset($arguments['resource'])) {
                $syliusOptions['resource'] = $arguments['resource'];
            }

            if (null !== $operation) {
                $syliusOptions['operation'] = $operation;
            }

            if (isset($arguments['provider'])) {
                $syliusOptions['provider'] = $arguments['provider'];
            }

            if (isset($arguments['processor'])) {
                $syliusOptions['processor'] = $arguments['processor'];
            }

            if (isset($arguments['read'])) {
                $syliusOptions['read'] = $arguments['read'];
            }

            if (isset($arguments['validate'])) {
                $syliusOptions['validate'] = $arguments['validate'];
            }

            if (isset($arguments['write'])) {
                $syliusOptions['write'] = $arguments['write'];
            }

            if (isset($arguments['respond'])) {
                $syliusOptions['respond'] = $arguments['respond'];
            }

            if (isset($arguments['serializationGroups'])) {
                $syliusOptions['serialization_groups'] = $arguments['serializationGroups'];
            }

            if (isset($arguments['serializationVersion'])) {
                $syliusOptions['serialization_version'] = $arguments['serializationVersion'];
            }

            if (isset($arguments['form'])) {
                $syliusOptions['form'] = $arguments['form'];
            }

            if (isset($arguments['section'])) {
                $syliusOptions['section'] = $arguments['section'];
            }

            if (isset($arguments['permission'])) {
                $syliusOptions['permission'] = $arguments['permission'];
            }

            if (isset($arguments['grid'])) {
                $syliusOptions['grid'] = $arguments['grid'];
            }

            if (isset($arguments['csrfProtection'])) {
                $syliusOptions['csrf_protection'] = $arguments['csrfProtection'];
            }

            if (isset($arguments['redirect'])) {
                $syliusOptions['redirect'] = $arguments['redirect'];
            }

            if (isset($arguments['stateMachine'])) {
                $syliusOptions['state_machine'] = $arguments['stateMachine'];
            }

            if (isset($arguments['event'])) {
                $syliusOptions['event'] = $arguments['event'];
            }

            if (isset($arguments['returnContent'])) {
                $syliusOptions['return_content'] = $arguments['returnContent'];
            }

            $metadata = isset($arguments['resource']) ? $this->resourceRegistry->get($arguments['resource']) : null;

            $route = new Route(
                $arguments['path'] ?? $this->getDefaultRoutePath($metadata, $arguments, $operation),
                [
                    '_controller' => $arguments['controller'] ?? PlaceHolderAction::class,
                    '_sylius' => $syliusOptions,
                ],
                $arguments['requirements'] ?? [],
                $arguments['options'] ?? [],
                $arguments['host'] ?? '',
                $arguments['schemes'] ?? [],
                $arguments['methods'] ?? $methods,
            );

            $routeCollection->add($arguments['name'] ?? $this->getDefaultRouteName($metadata, $arguments, $operation), $route, $arguments['priority'] ?? 0);
        }
    }

    private function getDefaultRouteName(?MetadataInterface $metadata, array $arguments, ?string $operation): string
    {
        Assert::notNull($operation, 'Impossible to get default route name without operation. Please define an operation.');
        Assert::notNull($metadata, 'Impossible to get default route name without resource. Please define a resource.');

        if (isset($arguments['section'])) {
            return sprintf('%s_%s_%s_%s', $metadata->getApplicationName(), $arguments['section'], $metadata->getName(), $operation);
        }

        return sprintf('%s_%s_%s', $metadata->getApplicationName(), $metadata->getName(), $operation);
    }

    private function getDefaultRoutePath(?MetadataInterface $metadata, array $arguments, ?string $operation): string
    {
        Assert::notNull($operation, 'Impossible to get default route path without operation. Please define an operation.');
        $defaultPath = $this->getDefaultRoutePathForOperation($metadata, $operation);

        if (isset($arguments['section'])) {
            return sprintf('%s/%s', $arguments['section'], $defaultPath);
        }

        return $defaultPath;
    }

    private function getDefaultRoutePathForOperation(?MetadataInterface $metadata, string $operation): string
    {
        Assert::notNull($metadata, 'Impossible to get default route path without resource. Please define a resource.');

        $rootPath = sprintf('%s', Urlizer::urlize($metadata->getPluralName()));

        if ('index' === $operation) {
            return sprintf('%s', $rootPath);
        }

        if ('create' === $operation) {
            return sprintf('%s/new', $rootPath);
        }

        if ('update' === $operation) {
            return sprintf('%s/{id}/edit', $rootPath);
        }

        if ('delete' === $operation) {
            return sprintf('%s/{id}', $rootPath);
        }

        if ('show' === $operation) {
            return sprintf('%s/{id}', $rootPath);
        }

        throw new \InvalidArgumentException(sprintf('Impossible to get a default route path for this route with operation "%s". Please define a path.', $operation));
    }
}
