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
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\TemplateRendererInterface;
use Sylius\Bundle\ResourceBundle\Creator\RestViewCreatorInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowAction
{
    protected MetadataInterface $metadata;

    protected RequestConfigurationFactoryInterface $requestConfigurationFactory;

    protected EventDispatcherInterface $eventDispatcher;

    protected RepositoryInterface $repository;

    protected SingleResourceProviderInterface $singleResourceProvider;

    protected TemplateRendererInterface $templateRenderer;

    protected RequestPermissionCheckerInterface $requestPermissionChecker;

    protected RestViewCreatorInterface $restViewCreator;

    public function __construct(
        MetadataInterface $metadata,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        EventDispatcherInterface $eventDispatcher,
        RepositoryInterface $repository,
        SingleResourceProviderInterface $singleResourceProvider,
        TemplateRendererInterface $templateRenderer,
        RequestPermissionCheckerInterface $requestPermissionChecker,
        RestViewCreatorInterface $restViewCreator
    ) {
        $this->metadata = $metadata;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->repository = $repository;
        $this->singleResourceProvider = $singleResourceProvider;
        $this->templateRenderer = $templateRenderer;
        $this->requestPermissionChecker = $requestPermissionChecker;
        $this->restViewCreator = $restViewCreator;
    }

    public function __invoke(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->requestPermissionChecker->isGrantedOr403($configuration, ResourceActions::SHOW);
        $resource = $this->findOr404($configuration);

        $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $resource);

        if ($configuration->isHtmlRequest()) {
            return new Response($this->templateRenderer->render($configuration->getTemplate(ResourceActions::SHOW . '.html'), [
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $resource,
                $this->metadata->getName() => $resource,
            ]));
        }

        return $this->restViewCreator->createRestView($configuration, $resource);
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
}
