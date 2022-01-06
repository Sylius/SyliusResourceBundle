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
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Checker\RequestPermissionCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Sylius\Component\Resource\Exception\UpdateHandlingException;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ApplyStateMachineTransitionAction
{
    protected MetadataInterface $metadata;

    protected RequestConfigurationFactoryInterface $requestConfigurationFactory;

    protected RepositoryInterface $repository;

    protected ObjectManager $manager;

    protected SingleResourceProviderInterface $singleResourceProvider;

    protected RedirectHandlerInterface $redirectHandler;

    protected FlashHelperInterface $flashHelper;

    protected EventDispatcherInterface $eventDispatcher;

    protected ResourceUpdateHandlerInterface $resourceUpdateHandler;

    protected CsrfTokenManagerInterface $csrfTokenManager;

    protected RequestPermissionCheckerInterface $requestPermissionChecker;

    protected ?ViewHandlerInterface $viewHandler;

    protected ?StateMachineInterface $stateMachine;

    public function __construct(
        MetadataInterface $metadata,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        RepositoryInterface $repository,
        ObjectManager $manager,
        SingleResourceProviderInterface $singleResourceProvider,
        RedirectHandlerInterface $redirectHandler,
        FlashHelperInterface $flashHelper,
        EventDispatcherInterface $eventDispatcher,
        ResourceUpdateHandlerInterface $resourceUpdateHandler,
        CsrfTokenManagerInterface $csrfTokenManager,
        RequestPermissionCheckerInterface $requestPermissionChecker,
        ?ViewHandlerInterface $viewHandler,
        ?StateMachineInterface $stateMachine
    ) {
        $this->metadata = $metadata;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->repository = $repository;
        $this->manager = $manager;
        $this->singleResourceProvider = $singleResourceProvider;
        $this->redirectHandler = $redirectHandler;
        $this->flashHelper = $flashHelper;
        $this->eventDispatcher = $eventDispatcher;
        $this->resourceUpdateHandler = $resourceUpdateHandler;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->requestPermissionChecker = $requestPermissionChecker;
        $this->viewHandler = $viewHandler;
        $this->stateMachine = $stateMachine;
    }

    public function __invoke(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->requestPermissionChecker->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $resource = $this->findOr404($configuration);

        if (
            $configuration->isCsrfProtectionEnabled() &&
            !$this->isCsrfTokenValid((string) $resource->getId(), $request->get('_csrf_token'))
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

        if (!$this->getStateMachine()->can($configuration, $resource)) {
            throw new BadRequestHttpException();
        }

        try {
            $this->resourceUpdateHandler->handle($resource, $configuration, $this->manager);
        } catch (UpdateHandlingException $exception) {
            if (!$configuration->isHtmlRequest()) {
                return $this->createRestView($configuration, $resource, $exception->getApiResponseCode());
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
                return $this->createRestView($configuration, $resource, Response::HTTP_OK);
            }

            return $this->createRestView($configuration, null, Response::HTTP_NO_CONTENT);
        }

        $postEventResponse = $postEvent->getResponse();
        if (null !== $postEventResponse) {
            return $postEventResponse;
        }

        return $this->redirectHandler->redirectToResource($configuration, $resource);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findOr404(RequestConfiguration $configuration): ResourceInterface
    {
        if (null === $resource = $this->singleResourceProvider->get($configuration, $this->repository)) {
            throw new NotFoundHttpException(sprintf('The "%s" has not been found', $this->metadata->getHumanizedName()));
        }

        return $resource;
    }

    protected function createRestView(RequestConfiguration $configuration, $data, int $statusCode = null): Response
    {
        if (null === $this->viewHandler) {
            throw new \LogicException('You can not use the "non-html" request if FriendsOfSymfony Rest Bundle is not available. Try running "composer require friendsofsymfony/rest-bundle".');
        }

        $view = View::create($data, $statusCode);

        return $this->viewHandler->handle($configuration, $view);
    }

    protected function getStateMachine(): StateMachineInterface
    {
        if (null === $this->stateMachine) {
            throw new \LogicException('You can not use the "state-machine" if Winzou State Machine Bundle is not available. Try running "composer require winzou/state-machine-bundle".');
        }

        return $this->stateMachine;
    }

    protected function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken($id, $token));
    }
}
