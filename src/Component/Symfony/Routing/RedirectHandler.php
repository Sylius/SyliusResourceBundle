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

namespace Sylius\Component\Resource\Symfony\Routing;

use Sylius\Component\Resource\Metadata\HttpOperation;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;

final class RedirectHandler
{
    public function __construct(private RouterInterface $router)
    {
    }

    public function redirectToResource(mixed $data, HttpOperation $operation, Request $request): RedirectResponse
    {
        $route = $operation->getRedirectToRoute();

        if (null === $route) {
            throw new \RuntimeException(sprintf('Operation "%s" has no redirection route, but it should.', $operation->getName() ?? ''));
        }

        $parameters = $this->parseResourceValues([], $data);

        return $this->redirectToRoute($data, $operation, $route, $parameters);
    }

    public function redirectToRoute(mixed $data, HttpOperation $operation, string $route, array $parameters = []): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($route, $parameters));
    }

    private function parseResourceValues(array $parameters, mixed $data): array
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        if (empty($parameters)) {
            if (\is_object($data) && $accessor->isReadable($data, 'id')) {
                return ['id' => $accessor->getValue($data, 'id')];
            }
        }

        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $parameters[$key] = $this->parseResourceValues($value, $data);
            }

            if (is_string($value) && str_starts_with($value, 'resource.')) {
                $parameters[$key] = $accessor->getValue($data, substr($value, 9));
            }
        }

        return $parameters;
    }
}
