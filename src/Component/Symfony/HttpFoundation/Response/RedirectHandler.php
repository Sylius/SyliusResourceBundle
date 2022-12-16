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

namespace Sylius\Component\Resource\Symfony\HttpFoundation\Response;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestConfigurationOption;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

final class RedirectHandler implements RedirectHandlerInterface
{
    public function __construct(private RouterInterface $router)
    {
    }

    public function redirectToResource(Operation $operation, Context $context, object $resource): Response
    {
        $configuration = $context->get(RequestConfigurationOption::class)?->configuration();

        try {
            return $this->redirectToRoute(
                $operation,
                $context,
                (string) $configuration?->getRedirectRoute(ResourceActions::SHOW),
                $configuration->getRedirectParameters($resource),
            );
        } catch (RouteNotFoundException) {
            return $this->redirectToRoute(
                $operation,
                $context,
                (string) $configuration?->getRedirectRoute(ResourceActions::INDEX),
                $configuration?->getRedirectParameters($resource) ?? [],
            );
        }
    }

    public function redirectToIndex(Operation $operation, Context $context, object $resource = null): Response
    {
        $configuration = $context->get(RequestConfigurationOption::class)?->configuration();

        return $this->redirectToRoute(
            $operation,
            $context,
            (string) $configuration?->getRedirectRoute('index'),
            $configuration->getRedirectParameters($resource),
        );
    }

    public function redirectToRoute(Operation $operation, Context $context, string $route, array $parameters = []): Response
    {
        if ('referer' === $route) {
            return $this->redirectToReferer($operation, $context);
        }

        return $this->redirect($operation, $context, $this->router->generate($route, $parameters));
    }

    public function redirect(Operation $operation, Context $context, string $url, int $status = 302): Response
    {
        $configuration = $context->get(RequestConfigurationOption::class)?->configuration();

        if ($configuration?->isHeaderRedirection() ?? false) {
            return new Response('', 200, [
                'X-SYLIUS-LOCATION' => $url . $configuration->getRedirectHash(),
            ]);
        }

        return new RedirectResponse($url . $configuration->getRedirectHash(), $status);
    }

    public function redirectToReferer(Operation $operation, Context $context): Response
    {
        $configuration = $context->get(RequestConfigurationOption::class)?->configuration();

        return $this->redirect($operation, $context, (string) $configuration?->getRedirectReferer());
    }
}
