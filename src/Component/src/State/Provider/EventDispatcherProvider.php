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

namespace Sylius\Resource\State\Provider;

use Sylius\Component\Resource\Metadata\CollectionOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\ShowOperationInterface;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\State\ProviderInterface;

/**
 * @experimental
 */
final class EventDispatcherProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $decorated,
        private OperationEventDispatcherInterface $operationEventDispatcher,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        if (
            $operation instanceof CollectionOperationInterface ||
            $operation instanceof ShowOperationInterface
        ) {
            $this->operationEventDispatcher->dispatch(null, $operation, $context);
        }

        return $this->decorated->provide($operation, $context);
    }
}
