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
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

final class CallableProcessor implements ProcessorInterface
{
    public function __construct(private ContainerInterface $locator)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, RequestConfiguration $configuration)
    {
        if (!($processor = $configuration->getProcessor())) {
            return null;
        }

        if (\is_callable($processor)) {
            return $processor($data, $configuration);
        }

        if (\is_string($processor)) {
            if (!$this->locator->has($processor)) {
                throw new \RuntimeException(sprintf('Processor "%s" not found on operation "%s"', $processor, $configuration->getName()));
            }

            /** @var ProcessorInterface $processor */
            $processor = $this->locator->get($processor);

            return $processor->process($data, $configuration);
        }

        return null;
    }
}
