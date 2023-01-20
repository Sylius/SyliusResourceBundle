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
        $processor = $operation->getProcessor()
        
        if (is_null($processor)) {
            return null;
        }

        if (\is_callable($processor)) {
            return $processor($data, $operation, $context);
        }

        if (!$this->locator->has($processor)) {
            throw new \RuntimeException(sprintf('Processor "%s" not found on operation "%s"', $processor, $operation->getName() ?? ''));
        }

        /** @var ProcessorInterface $processorInstance */
        $processorInstance = $this->locator->get($processor);

        return $processorInstance->process($data, $operation, $context);
    }
}
