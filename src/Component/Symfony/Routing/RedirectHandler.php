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

use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;

final class RedirectHandler
{
    public function __construct(
        private RouterInterface $router,
        private ArgumentParserInterface $argumentParser,
    ) {
    }

    public function redirectToResource(mixed $data, HttpOperation $operation, Request $request): RedirectResponse
    {
        $route = $operation->getRedirectToRoute();

        if (null === $route) {
            throw new \RuntimeException(sprintf('Operation "%s" has no redirection route, but it should.', $operation->getName() ?? ''));
        }

        $resource = $operation->getResource();

        if (null === $resource) {
            throw new \RuntimeException(sprintf('Operation "%s" has no resource, but it should.', $operation->getName() ?? ''));
        }

        $redirectArguments = $operation->getRedirectArguments() ?? [];

        if ([] === $redirectArguments && !$operation instanceof DeleteOperationInterface) {
            $identifier = $resource->getIdentifier() ?? 'id';

            $redirectArguments[$identifier] = 'resource.' . $identifier;
        }

        $parameters = $this->parseResourceValues($resource, $redirectArguments, $data);

        return $this->redirectToRoute($data, $route, $parameters);
    }

    public function redirectToRoute(mixed $data, string $route, array $parameters = []): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($route, $parameters));
    }

    private function parseResourceValues(Resource $resource, array $parameters, mixed $data): array
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($parameters as $key => $value) {
            if (str_contains($value, 'resource.')) {
                $propertyPath = substr($value, 9);

                if (\is_object($data) && $accessor->isReadable($data, $propertyPath)) {
                    $parameters[$key] = $accessor->getValue($data, $propertyPath);

                    continue;
                }
            }

            $variables = ['resource' => $data];
            $resourceName = $resource->getName();

            if (null !== $resourceName) {
                $variables[$resourceName] = $data;
            }

            $parameters[$key] = $this->argumentParser->parseExpression($value, $variables);
        }

        return $parameters;
    }
}
