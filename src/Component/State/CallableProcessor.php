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
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Doctrine\Common\State\PersistProcessor;
use Sylius\Component\Resource\Doctrine\Common\State\RemoveProcessor;

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
        if (\is_callable($processor = $configuration->getProcessor())) {
            return $processor($data, $configuration);
        }

        if (null === $processor) {
            $processor = $this->getDefaultProcessor($configuration);
        }

        if (\is_string($processor)) {
            if (!$this->locator->has($processor)) {
                throw new \RuntimeException(sprintf('Processor "%s" not found on operation "%s"', $processor, $configuration->getOperation()));
            }

            /** @var ProcessorInterface $processor */
            $processor = $this->locator->get($processor);

            return $processor->process($data, $configuration);
        }

        return null;
    }

    private function getDefaultProcessor(RequestConfiguration $configuration): ?string
    {
        $driver = $configuration->getMetadata()->getDriver();

        if (in_array($driver, [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
            SyliusResourceBundle::DRIVER_DOCTRINE_MONGODB_ODM,
            SyliusResourceBundle::DRIVER_DOCTRINE_PHPCR_ODM,
        ], true)) {
            if ($configuration->getOperation() === 'delete') {
                return RemoveProcessor::class;
            }

            return PersistProcessor::class;
        }

        return null;
    }
}
