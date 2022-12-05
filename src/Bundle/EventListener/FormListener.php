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

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Form\Factory\FormFactoryInterface;
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\Factory\ResourceMetadataFactoryInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Sylius\Component\Resource\Util\ContextInitiatorTrait;
use Sylius\Component\Resource\Util\OperationRequestInitiatorTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class FormListener
{
    use ContextInitiatorTrait;
    use OperationRequestInitiatorTrait;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private ResourceMetadataFactoryInterface $resourceMetadataFactory,
        private FormFactoryInterface $formFactory,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();
        $context = $this->initializeContext($request);

        if (
            $controllerResult instanceof Response ||
            (null === $configuration = $context->get(RequestConfiguration::class)) ||
            (null === $operation = $this->initializeOperation($request)) ||
            !($operation instanceof CreateOperationInterface || $operation instanceof UpdateOperationInterface) ||
            null === $configuration->getFormType()
        ) {
            return;
        }

        $form = $this->formFactory->create($configuration, $controllerResult);
        $form->handleRequest($request);

        $request->attributes->set('form', $form);
    }
}
