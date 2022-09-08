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
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Component\Resource\State\ProcessorInterface;
use Sylius\Component\Resource\Util\OperationRequestInitiatorTrait;
use Sylius\Component\Resource\Util\RequestConfigurationInitiatorTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class WriteListener
{
    use RequestConfigurationInitiatorTrait;
    use OperationRequestInitiatorTrait;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private ResourceMetadataFactoryInterface $resourceMetadataFactory,
        private ProcessorInterface $processor,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        /** @var Response|ResourceInterface $controllerResult */
        $controllerResult = $event->getControllerResult();

        $request = $event->getRequest();

        /** @var ResourceControllerEvent|null $resourceEvent */
        $resourceEvent = $request->attributes->get('resource_event');

        if (
            $controllerResult instanceof Response ||
            (null === $configuration = $this->initializeConfiguration($request)) ||
            (null === $operation = $this->initializeOperation($request)) ||
            !($operation->canWrite() ?? true) ||
            in_array($operation->getAction(), [ResourceActions::INDEX, ResourceActions::SHOW], true) ||
            !$request->attributes->getBoolean('is_valid', true) ||
            null !== $resourceEvent && $resourceEvent->isStopped()
        ) {
            return;
        }

        switch($request->getMethod()) {
            case 'PUT':
            case 'PATCH':
            case 'POST':
                $persistResult = $this->processor->process($controllerResult, $operation, $configuration);

                if ($persistResult) {
                    $controllerResult = $persistResult;
                    $event->setControllerResult($controllerResult);
                }

                break;
            case 'DELETE':
                $this->processor->process($controllerResult, $operation, $configuration);
                $event->setControllerResult(null);
                break;
        }
    }
}
