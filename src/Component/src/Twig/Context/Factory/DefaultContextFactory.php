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

namespace Sylius\Resource\Twig\Context\Factory;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\CollectionOperationInterface;
use Sylius\Resource\Metadata\Operation;

/**
 * @experimental
 */
final class DefaultContextFactory implements ContextFactoryInterface
{
    public function create(mixed $data, Operation $operation, Context $context): array
    {
        $twigContext = [
            'operation' => $operation,
            'resource_metadata' => $operation->getResource(),
        ];

        if ($operation instanceof CollectionOperationInterface) {
            $twigContext['resources'] = $data;
            $pluralName = $operation->getResource()?->getPluralName();

            if (null !== $pluralName) {
                $twigContext[$pluralName] = $data;
            }
        } else {
            $twigContext['resource'] = $data;
            $name = $operation->getResource()?->getName();

            if (null !== $name) {
                $twigContext[$name] = $data;
            }
        }

        return $twigContext;
    }
}
