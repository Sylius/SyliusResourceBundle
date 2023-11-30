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

namespace Sylius\Bundle\ResourceBundle\Routing;

use Sylius\Component\Resource\Annotation\SyliusRoute as LegacySyliusRoute;
use Sylius\Resource\Annotation\SyliusRoute;
use Sylius\Resource\Reflection\ClassReflection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;

final class RouteAttributesFactory implements RouteAttributesFactoryInterface
{
    public function createRouteForClass(RouteCollection $routeCollection, string $className): void
    {
        $attributes = ClassReflection::getClassAttributes($className, SyliusRoute::class);
        $attributes = array_merge($attributes, ClassReflection::getClassAttributes($className, LegacySyliusRoute::class));

        foreach ($attributes as $reflectionAttribute) {
            $arguments = $reflectionAttribute->getArguments();

            Assert::keyExists($arguments, 'name', 'Your route should have a name attribute.');

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
                $arguments['path'],
                [
                    '_controller' => $arguments['controller'] ?? null,
                    '_sylius' => $syliusOptions,
                ],
                $arguments['requirements'] ?? [],
                $arguments['options'] ?? [],
                $arguments['host'] ?? '',
                $arguments['schemes'] ?? [],
                $arguments['methods'] ?? [],
            );

            $routeCollection->add($arguments['name'], $route, $arguments['priority'] ?? 0);
        }
    }
}
