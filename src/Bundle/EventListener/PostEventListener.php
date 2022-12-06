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
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Context\Option\RequestConfigurationOption;
use Sylius\Component\Resource\Metadata\CollectionOperationInterface;
use Sylius\Component\Resource\Metadata\Factory\ResourceMetadataFactoryInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\ShowOperationInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Util\ContextInitiatorTrait;
use Sylius\Component\Resource\Util\OperationRequestInitiatorTrait;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class PostEventListener
{
    use ContextInitiatorTrait;
    use OperationRequestInitiatorTrait;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private ResourceMetadataFactoryInterface $resourceMetadataFactory,
        private EventDispatcherInterface $eventDispatcher,
        private FlashHelperInterface $flashHelper,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();
        $controllerResult = $event->getControllerResult();
        $context = $this->initializeContext($request);

        $requestConfigurationOption = $context->get(RequestConfigurationOption::class);

        if (
            !$controllerResult instanceof ResourceInterface ||
            (null === $configuration = $requestConfigurationOption?->configuration()) ||
            (null === $operation = $this->initializeOperation($request)) ||
            !$request->attributes->getBoolean('is_valid', true)
        ) {
            return;
        }

        $resourceEvent = $this->eventDispatcher->dispatchPostEvent($operation->getName(), $configuration, $controllerResult);
        $request->attributes->set('resource_post_event', $resourceEvent);

        if (null !== $postEventResponse = $resourceEvent->getResponse()) {
            $event->setControllerResult($postEventResponse);
        }

        if (
            $operation instanceof ShowOperationInterface ||
            $operation instanceof CollectionOperationInterface
        ) {
            return;
        }

        $this->flashHelper->addSuccessFlash($configuration, $operation->getName(), $controllerResult);
    }
}
