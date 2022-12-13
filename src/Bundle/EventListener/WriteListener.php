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

use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Metadata\Factory\ResourceMetadataFactoryInterface;
use Sylius\Component\Resource\Metadata\Operation\Initiator\OperationRequestInitiator;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\State\ProcessorInterface;
use Sylius\Component\Resource\Util\ContextInitiatorTrait;
use Sylius\Component\Resource\Util\OperationRequestInitiatorTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class WriteListener
{
    use ContextInitiatorTrait;

    public function __construct(
        private OperationRequestInitiator $operationRequestInitiator,
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private ProcessorInterface $processor,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        /** @var Response|ResourceInterface $controllerResult */
        $controllerResult = $event->getControllerResult();

        $request = $event->getRequest();
        $context = $this->initializeContext($request);

        /** @var ResourceControllerEvent|null $resourceEvent */
        $resourceEvent = $request->attributes->get('resource_event');

        if (
            $controllerResult instanceof Response ||
            (null === $operation = $this->operationRequestInitiator->initializeOperation($request)) ||
            !($operation->canWrite() ?? true) ||
            'GET' === $request->getMethod() ||
            !$request->attributes->getBoolean('is_valid', true) ||
            null !== $resourceEvent && $resourceEvent->isStopped()
        ) {
            return;
        }

        switch ($request->getMethod()) {
            case 'PUT':
            case 'PATCH':
            case 'POST':
                $persistResult = $this->processor->process($controllerResult, $operation, $context);

                if ($persistResult) {
                    $controllerResult = $persistResult;
                    $event->setControllerResult($controllerResult);
                }

                break;
            case 'DELETE':
                $this->processor->process($controllerResult, $operation, $context);
                $event->setControllerResult(null);

                break;
        }
    }
}
