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

use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Checker\RequestPermissionCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Sylius\Component\Resource\Exception\DeleteHandlingException;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class BulkDeleteAction
{
    protected MetadataInterface $metadata;

    protected RequestConfigurationFactoryInterface $requestConfigurationFactory;

    protected ResourcesCollectionProviderInterface $resourcesCollectionProvider;

    protected RepositoryInterface $repository;

    protected RedirectHandlerInterface $redirectHandler;

    protected FlashHelperInterface $flashHelper;

    protected EventDispatcherInterface $eventDispatcher;

    protected ResourceDeleteHandlerInterface $resourceDeleteHandler;

    protected CsrfTokenManagerInterface $csrfTokenManager;

    protected RequestPermissionCheckerInterface $requestPermissionChecker;

    protected ?ViewHandlerInterface $viewHandler;

    public function __construct(
        MetadataInterface $metadata,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        ResourcesCollectionProviderInterface $resourcesCollectionProvider,
        RepositoryInterface $repository,
        RedirectHandlerInterface $redirectHandler,
        FlashHelperInterface $flashHelper,
        EventDispatcherInterface $eventDispatcher,
        ResourceDeleteHandlerInterface $resourceDeleteHandler,
        CsrfTokenManagerInterface $csrfTokenManager,
        RequestPermissionCheckerInterface $requestPermissionChecker,
        ?ViewHandlerInterface $viewHandler
    ) {
        $this->metadata = $metadata;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->resourcesCollectionProvider = $resourcesCollectionProvider;
        $this->repository = $repository;
        $this->redirectHandler = $redirectHandler;
        $this->flashHelper = $flashHelper;
        $this->eventDispatcher = $eventDispatcher;
        $this->resourceDeleteHandler = $resourceDeleteHandler;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->requestPermissionChecker = $requestPermissionChecker;
        $this->viewHandler = $viewHandler;
    }

    public function __invoke(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->requestPermissionChecker->isGrantedOr403($configuration, ResourceActions::BULK_DELETE);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        if (
            $configuration->isCsrfProtectionEnabled() &&
            !$this->isCsrfTokenValid(ResourceActions::BULK_DELETE, (string) $request->request->get('_csrf_token'))
        ) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid csrf token.');
        }

        $this->eventDispatcher->dispatchMultiple(ResourceActions::BULK_DELETE, $configuration, $resources);

        foreach ($resources as $resource) {
            $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::DELETE, $configuration, $resource);

            if ($event->isStopped() && !$configuration->isHtmlRequest()) {
                throw new HttpException($event->getErrorCode(), $event->getMessage());
            }
            if ($event->isStopped()) {
                $this->flashHelper->addFlashFromEvent($configuration, $event);

                $eventResponse = $event->getResponse();
                if (null !== $eventResponse) {
                    return $eventResponse;
                }

                return $this->redirectHandler->redirectToIndex($configuration, $resource);
            }

            try {
                $this->resourceDeleteHandler->handle($resource, $this->repository);
            } catch (DeleteHandlingException $exception) {
                if (!$configuration->isHtmlRequest()) {
                    return $this->createRestView($configuration, null, $exception->getApiResponseCode());
                }

                $this->flashHelper->addErrorFlash($configuration, $exception->getFlash());

                return $this->redirectHandler->redirectToReferer($configuration);
            }

            $postEvent = $this->eventDispatcher->dispatchPostEvent(ResourceActions::DELETE, $configuration, $resource);
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->createRestView($configuration, null, Response::HTTP_NO_CONTENT);
        }

        $this->flashHelper->addSuccessFlash($configuration, ResourceActions::BULK_DELETE);

        if (isset($postEvent)) {
            $postEventResponse = $postEvent->getResponse();
            if (null !== $postEventResponse) {
                return $postEventResponse;
            }
        }

        return $this->redirectHandler->redirectToIndex($configuration);
    }

    protected function createRestView(RequestConfiguration $configuration, $data, int $statusCode = null): Response
    {
        if (null === $this->viewHandler) {
            throw new \LogicException('You can not use the "non-html" request if FriendsOfSymfony Rest Bundle is not available. Try running "composer require friendsofsymfony/rest-bundle".');
        }

        $view = View::create($data, $statusCode);

        return $this->viewHandler->handle($configuration, $view);
    }

    protected function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken($id, $token));
    }
}
