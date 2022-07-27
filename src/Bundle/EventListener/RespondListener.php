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

use Pagerfanta\PagerfantaInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Twig\Environment;

final class RespondListener
{
    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private ?Environment $twig,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();

        $attributes = $request->attributes->get('_sylius');
        $alias = $attributes['alias'] ?? null;

        if (null === $alias) {
            return;
        }

        $metadata = $this->resourceRegistry->get($alias);
        $configuration = $this->requestConfigurationFactory->create($metadata, $request);

        /** @var PagerfantaInterface $data */
        $data = $request->attributes->get('data');

        if (!$request->isXmlHttpRequest()) {
        }

        $context = [];

        if ('index' === $configuration->getOperation()) {
            $context = ['resources' => $data];
        }

        if ('show' === $configuration->getOperation()) {
            $context = ['resource' => $data];
        }

        $content = $this->twig->render($configuration->getTemplate($configuration->getOperation()), $context);

        $response = new Response();
        $response->setContent($content);

        $event->setResponse($response);
    }
}
