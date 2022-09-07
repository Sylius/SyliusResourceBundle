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

use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Metadata\Factory\OperationFactoryInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Util\OperationRequestInitiatorTrait;
use Sylius\Component\Resource\Util\RequestConfigurationInitiatorTrait;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class PreEventListener
{
    use RequestConfigurationInitiatorTrait;
    use OperationRequestInitiatorTrait;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private OperationFactoryInterface $operationFactory,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();
        $controllerResult = $event->getControllerResult();

        if (
            !$controllerResult instanceof ResourceInterface ||
            (null === $configuration = $this->initializeConfiguration($request)) ||
            (null === $operation = $this->initializeOperation($request)) ||
            !$request->attributes->getBoolean('is_valid', true)
        ) {
            return;
        }

        $event = $this->eventDispatcher->dispatchPreEvent($operation->getAction(), $configuration, $controllerResult);

        $request->attributes->set('resource_event', $event);
    }
}
