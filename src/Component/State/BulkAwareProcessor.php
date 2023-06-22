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

namespace Sylius\Component\Resource\State;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\BulkOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;

/**
 * @experimental
 */
final class BulkAwareProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decorated,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        if (
            !$operation instanceof BulkOperationInterface ||
            !\is_iterable($data)
        ) {
            return $this->decorated->process($data, $operation, $context);
        }

        foreach ($data as $item) {
            $this->decorated->process($item, $operation, $context);
        }

        return null;
    }
}
