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

namespace Sylius\Bundle\ResourceBundle\Controller\Action;

use Sylius\Bundle\ResourceBundle\Checker\RequestPermissionCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\TemplateRendererInterface;
use Sylius\Bundle\ResourceBundle\Creator\RestViewCreatorInterface;
use Sylius\Bundle\ResourceBundle\Provider\StateMachineProviderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CreateAction
{
    private MetadataInterface $metadata;

    private RequestConfigurationFactoryInterface $requestConfigurationFactory;

    private RepositoryInterface $repository;

    private FactoryInterface $factory;

    private NewResourceFactoryInterface $newResourceFactory;

    private ResourceFormFactoryInterface $resourceFormFactory;

    private RedirectHandlerInterface $redirectHandler;

    private FlashHelperInterface $flashHelper;

    private EventDispatcherInterface $eventDispatcher;

    private TemplateRendererInterface $templateRenderer;

    private RequestPermissionCheckerInterface $requestPermissionChecker;

    private StateMachineProviderInterface $stateMachineProvider;

    private RestViewCreatorInterface $restViewCreator;

    public function __construct(
        MetadataInterface $metadata,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        RepositoryInterface $repository,
        FactoryInterface $factory,
        NewResourceFactoryInterface $newResourceFactory,
        ResourceFormFactoryInterface $resourceFormFactory,
        RedirectHandlerInterface $redirectHandler,
        FlashHelperInterface $flashHelper,
        EventDispatcherInterface $eventDispatcher,
        TemplateRendererInterface $templateRenderer,
        RequestPermissionCheckerInterface $requestPermissionChecker,
        StateMachineProviderInterface $stateMachineProvider,
        RestViewCreatorInterface $restViewCreator
    ) {
        $this->metadata = $metadata;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->newResourceFactory = $newResourceFactory;
        $this->resourceFormFactory = $resourceFormFactory;
        $this->redirectHandler = $redirectHandler;
        $this->flashHelper = $flashHelper;
        $this->eventDispatcher = $eventDispatcher;
        $this->templateRenderer = $templateRenderer;
        $this->requestPermissionChecker = $requestPermissionChecker;
        $this->stateMachineProvider = $stateMachineProvider;
        $this->restViewCreator = $restViewCreator;
    }

    public function __invoke(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->requestPermissionChecker->isGrantedOr403($configuration, ResourceActions::CREATE);
        $newResource = $this->newResourceFactory->create($configuration, $this->factory);

        $form = $this->resourceFormFactory->create($configuration, $newResource);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            $newResource = $form->getData();

            $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);

            if ($event->isStopped() && !$configuration->isHtmlRequest()) {
                throw new HttpException($event->getErrorCode(), $event->getMessage());
            }
            if ($event->isStopped()) {
                $this->flashHelper->addFlashFromEvent($configuration, $event);

                $eventResponse = $event->getResponse();
                if (null !== $eventResponse) {
                    return $eventResponse;
                }

                return $this->redirectHandler->redirectToIndex($configuration, $newResource);
            }

            if ($configuration->hasStateMachine()) {
                $stateMachine = $this->stateMachineProvider->getStateMachine();
                $stateMachine->apply($configuration, $newResource);
            }

            $this->repository->add($newResource);

            if ($configuration->isHtmlRequest()) {
                $this->flashHelper->addSuccessFlash($configuration, ResourceActions::CREATE, $newResource);
            }

            $postEvent = $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);

            if (!$configuration->isHtmlRequest()) {
                return $this->restViewCreator->createRestView($configuration, $newResource, Response::HTTP_CREATED);
            }

            $postEventResponse = $postEvent->getResponse();
            if (null !== $postEventResponse) {
                return $postEventResponse;
            }

            return $this->redirectHandler->redirectToResource($configuration, $newResource);
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->restViewCreator->createRestView($configuration, $form, Response::HTTP_BAD_REQUEST);
        }

        $initializeEvent = $this->eventDispatcher->dispatchInitializeEvent(ResourceActions::CREATE, $configuration, $newResource);
        $initializeEventResponse = $initializeEvent->getResponse();
        if (null !== $initializeEventResponse) {
            return $initializeEventResponse;
        }

        return new Response($this->templateRenderer->render($configuration->getTemplate(ResourceActions::CREATE . '.html'), [
            'configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource' => $newResource,
            $this->metadata->getName() => $newResource,
            'form' => $form->createView(),
        ]));
    }
}
