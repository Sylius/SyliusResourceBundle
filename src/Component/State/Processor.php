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

namespace Sylius\Component\Resource\State;

use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\BulkOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Webmozart\Assert\Assert;

/**
 * @experimental
 */
final class Processor implements ProcessorInterface
{
    public function __construct(
        private ContainerInterface $locator,
        private OperationEventDispatcherInterface $operationEventDispatcher,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        $processor = $operation->getProcessor();

        if (null === $processor) {
            return null;
        }

        return $this->processWithProcessor($processor, $data, $operation, $context);
    }

    private function processWithProcessor(callable|string $processor, mixed $data, Operation $operation, Context $context): mixed
    {
        /** @var ProcessorInterface|null $processorInstance */
        $processorInstance = null;

        if (\is_string($processor)) {
            $processorInstance = $this->locator->get($processor);
            Assert::isInstanceOf($processorInstance, ProcessorInterface::class);
        }

        if ($operation instanceof BulkOperationInterface && \is_iterable($data)) {
            $this->operationEventDispatcher->dispatchBulkEvent($data, $operation, $context);

            foreach ($data as $item) {
                $this->operationEventDispatcher->dispatchPreEvent($item, $operation, $context);

                if (null === $processorInstance) {
                    if (\is_callable($processor)) {
                        $processor($item, $operation, $context);
                    }
                } else {
                    $processorInstance->process($item, $operation, $context);
                }

                $this->operationEventDispatcher->dispatchPostEvent($item, $operation, $context);
            }

            return null;
        }

        $this->operationEventDispatcher->dispatchPreEvent($data, $operation, $context);

        $result = null;

        if (null === $processorInstance) {
            if (\is_callable($processor)) {
                $result = $processor($data, $operation, $context);
            }
        } else {
            $result = $processorInstance->process($data, $operation, $context);
        }

        $this->operationEventDispatcher->dispatchPostEvent($data, $operation, $context);

        return $result;
    }
}
