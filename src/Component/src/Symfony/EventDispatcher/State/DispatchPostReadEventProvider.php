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

namespace Sylius\Resource\Symfony\EventDispatcher\State;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\CollectionOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\ShowOperationInterface;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;

/**
 * @experimental
 */
final class DispatchPostReadEventProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $provider,
        private OperationEventDispatcherInterface $operationEventDispatcher,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        $data = $this->provider->provide($operation, $context);

        if (
            $operation instanceof CollectionOperationInterface ||
            $operation instanceof ShowOperationInterface
        ) {
            $this->operationEventDispatcher->dispatch(null, $operation, $context);
        }

        return $data;
    }
}
