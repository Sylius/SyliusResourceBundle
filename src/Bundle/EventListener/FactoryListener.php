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

namespace Sylius\Bundle\ResourceBundle\EventListener;

use Psr\Container\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactory;
use Sylius\Component\Resource\Context\Initiator\RequestContextInitiator;
use Sylius\Component\Resource\Context\Option\RequestConfigurationOption;
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\Operation\Initiator\RequestOperationInitiator;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class FactoryListener
{
    public function __construct(
        private RequestOperationInitiator $operationInitiator,
        private RequestContextInitiator $contextInitiator,
        private ContainerInterface $factoryLocator,
        private NewResourceFactory $newResourceFactory,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $context = $this->contextInitiator->initializeContext($request);

        $requestConfigurationOption = $context->get(RequestConfigurationOption::class);

        if (
            (null === $configuration = $requestConfigurationOption?->configuration()) ||
            (null === $operation = $this->operationInitiator->initializeOperation($request)) ||
            false === $configuration->getFactoryMethod() ||
            null !== $operation->getInput() ||
            !$operation instanceof CreateOperationInterface
        ) {
            return;
        }

        $factoryId = $configuration->getMetadata()->getServiceId('factory');

        if (!$this->factoryLocator->has($factoryId)) {
            throw new \RuntimeException(sprintf('Factory "%s" not found on operation "%s"', $factoryId, $operation->getName()));
        }

        $data = $this->newResourceFactory->create($configuration, $this->factoryLocator->get($factoryId));

        $request->attributes->set('data', $data);
    }
}
