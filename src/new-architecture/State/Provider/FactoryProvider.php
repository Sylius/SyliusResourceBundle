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

use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\FactoryAwareOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\FactoryInterface;
use Sylius\Resource\State\ProviderInterface;

final class FactoryProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $decorated,
        private FactoryInterface $factory,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        $data = $this->decorated->provide($operation, $context);

        $request = $context->get(RequestOption::class)?->request();

        if (
            !$operation instanceof FactoryAwareOperationInterface ||
            !($operation->getFactory() ?? true)
        ) {
            return $data;
        }

        $data = $this->factory->create($operation, $context);

        $request?->attributes->set('data', $data);

        return $data;
    }
}
