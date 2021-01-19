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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\Helper;

use Sylius\Component\Resource\Model\ResourceInterface;

final class TargetEntitiesResolver implements TargetEntitiesResolverInterface
{
    public function resolve(array $resources): array
    {
        $interfaces = [];

        foreach ($resources as $alias => $configuration) {
            $model = $this->getModel($alias, $configuration);

            $modelInterfaces = class_implements($model) ?: [];

            foreach ($modelInterfaces as $interface) {
                if ($interface === ResourceInterface::class) {
                    continue;
                }

                $interfaces[$interface][] = $model;
            }
        }

        $interfaces = array_filter($interfaces, static function (array $classes): bool {
            return count($classes) === 1;
        });

        $interfaces = array_map(static function (array $classes): string {
            return (string) current($classes);
        }, $interfaces);

        foreach ($resources as $alias => $configuration) {
            if (isset($configuration['classes']['interface'])) {
                $model = $this->getModel($alias, $configuration);
                $interface = $configuration['classes']['interface'];

                @trigger_error(
                    sprintf(
                        'Specifying interface for resources is deprecated since ResourceBundle v1.6 and will be removed in v2.0. ' .
                        'Please rely on autodiscovering entity interfaces instead. ' .
                        'Triggered by resource "%s" with model "%s" and interface "%s".',
                        $alias,
                        $model,
                        $interface
                    ),
                    \E_USER_DEPRECATED
                );

                $interfaces[$interface] = $model;
            }
        }

        return $interfaces;
    }

    private function getModel(string $alias, array $configuration): string
    {
        if (!isset($configuration['classes']['model'])) {
            throw new \InvalidArgumentException(sprintf('Could not get model class from resource "%s".', $alias));
        }

        return $configuration['classes']['model'];
    }
}
