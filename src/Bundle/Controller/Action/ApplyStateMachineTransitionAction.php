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

use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\ResourceBundle\Checker\RequestPermissionCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandlerInterface;
use Sylius\Bundle\ResourceBundle\Creator\RestViewCreatorInterface;
use Sylius\Bundle\ResourceBundle\Finder\SingleResourceFinderInterface;
use Sylius\Bundle\ResourceBundle\Provider\StateMachineProviderInterface;
use Sylius\Component\Resource\Exception\UpdateHandlingException;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class ApplyStateMachineTransitionAction
{
    private MetadataInterface $metadata;

    private RequestConfigurationFactoryInterface $requestConfigurationFactory;

    private RepositoryInterface $repository;

    private ObjectManager $manager;

    private SingleResourceFinderInterface $singleResourceFinder;

    private RedirectHandlerInterface $redirectHandler;

    private FlashHelperInterface $flashHelper;

    private EventDispatcherInterface $eventDispatcher;

    private ResourceUpdateHandlerInterface $resourceUpdateHandler;

    private CsrfTokenManagerInterface $csrfTokenManager;

    private RequestPermissionCheckerInterface $requestPermissionChecker;

    private StateMachineProviderInterface $stateMachineProvider;

    private RestViewCreatorInterface $restViewCreator;

    public function __construct(
        MetadataInterface $metadata,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        RepositoryInterface $repository,
        ObjectManager $manager,
        SingleResourceFinderInterface $singleResourceFinder,
        RedirectHandlerInterface $redirectHandler,
        FlashHelperInterface $flashHelper,
        EventDispatcherInterface $eventDispatcher,
        ResourceUpdateHandlerInterface $resourceUpdateHandler,
        CsrfTokenManagerInterface $csrfTokenManager,
        RequestPermissionCheckerInterface $requestPermissionChecker,
        StateMachineProviderInterface $stateMachineProvider,
        RestViewCreatorInterface $restViewCreator
    ) {
        $this->metadata = $metadata;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->repository = $repository;
        $this->manager = $manager;
        $this->singleResourceFinder = $singleResourceFinder;
        $this->redirectHandler = $redirectHandler;
        $this->flashHelper = $flashHelper;
        $this->eventDispatcher = $eventDispatcher;
        $this->resourceUpdateHandler = $resourceUpdateHandler;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->requestPermissionChecker = $requestPermissionChecker;
        $this->stateMachineProvider = $stateMachineProvider;
        $this->restViewCreator = $restViewCreator;
    }

    public function __invoke(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->requestPermissionChecker->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $resource = $this->singleResourceFinder->findOr404($configuration, $this->repository, $this->metadata->getHumanizedName());

        if (
            $configuration->isCsrfProtectionEnabled() &&
            !$this->isCsrfTokenValid((string) $resource->getId(), (string) $request->request->get('_csrf_token'))
        ) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid CSRF token.');
        }

        $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $resource);

        if ($event->isStopped() && !$configuration->isHtmlRequest()) {
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }
        if ($event->isStopped()) {
            $this->flashHelper->addFlashFromEvent($configuration, $event);

            $eventResponse = $event->getResponse();
            if (null !== $eventResponse) {
                return $eventResponse;
            }

            return $this->redirectHandler->redirectToResource($configuration, $resource);
        }

        $stateMachine = $this->stateMachineProvider->getStateMachine();
        if (!$stateMachine->can($configuration, $resource)) {
            throw new BadRequestHttpException();
        }

        try {
            $this->resourceUpdateHandler->handle($resource, $configuration, $this->manager);
        } catch (UpdateHandlingException $exception) {
            if (!$configuration->isHtmlRequest()) {
                return $this->restViewCreator->createRestView($configuration, $resource, $exception->getApiResponseCode());
            }

            $this->flashHelper->addErrorFlash($configuration, $exception->getFlash());

            return $this->redirectHandler->redirectToReferer($configuration);
        }

        if ($configuration->isHtmlRequest()) {
            $this->flashHelper->addSuccessFlash($configuration, ResourceActions::UPDATE, $resource);
        }

        $postEvent = $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $resource);

        if (!$configuration->isHtmlRequest()) {
            if ($configuration->getParameters()->get('return_content', true)) {
                return $this->restViewCreator->createRestView($configuration, $resource, Response::HTTP_OK);
            }

            return $this->restViewCreator->createRestView($configuration, null, Response::HTTP_NO_CONTENT);
        }

        $postEventResponse = $postEvent->getResponse();
        if (null !== $postEventResponse) {
            return $postEventResponse;
        }

        return $this->redirectHandler->redirectToResource($configuration, $resource);
    }

    private function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken($id, $token));
    }
}
