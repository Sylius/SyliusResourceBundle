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
use Sylius\Component\Resource\Metadata\Operation;

final class CallableProcessor implements ProcessorInterface
{
    public function __construct(private ContainerInterface $locator)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, Context $context)
    {
        if (\is_callable($processor = $operation->getProcessor())) {
            return $processor($data, $operation, $context);
        }

        if (\is_string($processor)) {
            if (!$this->locator->has($processor)) {
                throw new \RuntimeException(sprintf('Processor "%s" not found on operation "%s"', $processor, $configuration->getOperation()));
            }

            /** @var ProcessorInterface $processor */
            $processor = $this->locator->get($processor);

            return $processor->process($data, $operation, $context);
        }

        return null;
    }
}
