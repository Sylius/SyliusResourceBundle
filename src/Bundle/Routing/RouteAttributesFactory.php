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

use Sylius\Component\Resource\Action\PlaceHolderAction;
use Sylius\Component\Resource\Annotation\SyliusRoute;
use Sylius\Component\Resource\Reflection\ClassReflection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class RouteAttributesFactory implements RouteAttributesFactoryInterface
{
    public function createRouteForClass(RouteCollection $routeCollection, string $className): void
    {
        $this->createRouteForAttributes($routeCollection, ClassReflection::getClassAttributes($className, SyliusRoute::class));
    }

    /** @param \ReflectionAttribute[] $attributes */
    private function createRouteForAttributes(RouteCollection $routeCollection, array $attributes, ?string $operation = null, array $methods = []): void
    {
        foreach ($attributes as $attribute) {
            $arguments = $attribute->getArguments();

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

            $route = new Route(
                $arguments['path'] ?? '',
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

            $routeCollection->add($arguments['name'] ?? '', $route, $arguments['priority'] ?? 0);
        }
    }
}
