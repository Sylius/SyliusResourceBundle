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
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\TemplateRendererInterface;
use Sylius\Bundle\ResourceBundle\Creator\RestViewCreatorInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class IndexAction
{
    private MetadataInterface $metadata;

    private RequestConfigurationFactoryInterface $requestConfigurationFactory;

    private EventDispatcherInterface $eventDispatcher;

    private RepositoryInterface $repository;

    private ResourcesCollectionProviderInterface $resourcesCollectionProvider;

    private TemplateRendererInterface $templateRenderer;

    private RequestPermissionCheckerInterface $requestPermissionChecker;

    private RestViewCreatorInterface $restViewCreator;

    public function __construct(
        MetadataInterface $metadata,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        EventDispatcherInterface $eventDispatcher,
        RepositoryInterface $repository,
        ResourcesCollectionProviderInterface $resourcesCollectionProvider,
        TemplateRendererInterface $templateRenderer,
        RequestPermissionCheckerInterface $requestPermissionChecker,
        RestViewCreatorInterface $restViewCreator
    ) {
        $this->metadata = $metadata;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->repository = $repository;
        $this->resourcesCollectionProvider = $resourcesCollectionProvider;
        $this->templateRenderer = $templateRenderer;
        $this->requestPermissionChecker = $requestPermissionChecker;
        $this->restViewCreator = $restViewCreator;
    }

    public function __invoke(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->requestPermissionChecker->isGrantedOr403($configuration, ResourceActions::INDEX);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $this->eventDispatcher->dispatchMultiple(ResourceActions::INDEX, $configuration, $resources);

        if (!$configuration->isHtmlRequest()) {
            return $this->restViewCreator->createRestView($configuration, $resources);
        }

        return new Response($this->templateRenderer->render($configuration->getTemplate(ResourceActions::INDEX . '.html'), [
            'configuration' => $configuration,
            'metadata' => $this->metadata,
            'resources' => $resources,
            $this->metadata->getPluralName() => $resources,
        ]));
    }
}
