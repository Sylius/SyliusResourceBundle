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
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Context\Option\RequestConfigurationOption;
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\Factory\ResourceMetadataFactoryInterface;
use Sylius\Component\Resource\Metadata\Operation\Initiator\OperationRequestInitiator;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Util\ContextInitiatorTrait;
use Sylius\Component\Resource\Util\OperationRequestInitiatorTrait;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class FactoryListener
{
    use ContextInitiatorTrait;

    public function __construct(
        private OperationRequestInitiator $operationRequestInitiator,
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private ContainerInterface $factoryLocator,
        private NewResourceFactory $newResourceFactory,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $context = $this->initializeContext($request);

        $requestConfigurationOption = $context->get(RequestConfigurationOption::class);

        if (
            (null === $configuration = $requestConfigurationOption?->configuration()) ||
            (null === $operation = $this->operationRequestInitiator->initializeOperation($request)) ||
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
