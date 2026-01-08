<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Resource\Symfony\Routing;

use Sylius\Bundle\GridBundle\Storage\FilterStorageInterface;
use Sylius\Resource\Exception\InvalidArgumentException;
use Sylius\Resource\Metadata\BulkOperationInterface;
use Sylius\Resource\Metadata\DeleteOperationInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;

/**
 * @experimental
 */
final class RedirectHandler implements RedirectHandlerInterface
{
    public function __construct(
        private RouterInterface $router,
        private ArgumentParserInterface $argumentParser,
        private OperationRouteNameFactoryInterface $operationRouteNameFactory,
        private ?FilterStorageInterface $filterStorage = null,
    ) {
    }

    public function redirectToResource(mixed $data, HttpOperation $operation, Request $request): RedirectResponse
    {
        $route = $operation->getRedirectToRoute();

        if (null === $route) {
            throw new \RuntimeException(sprintf('Operation "%s" has no redirection route, but it should.', $operation->getName() ?? ''));
        }

        $parameters = $this->getRouteArguments($data, $operation);

        return $this->redirectToRoute($data, $route, $parameters);
    }

    public function redirectToOperation(mixed $data, HttpOperation $operation, Request $request, string $newOperation): RedirectResponse
    {
        $route = $this->operationRouteNameFactory->createRouteName($operation, $newOperation);

        $parameters = $this->getRouteArguments($data, $operation);

        return $this->redirectToRoute($data, $route, $parameters);
    }

    public function redirectToRoute(mixed $data, string $route, array $parameters = []): RedirectResponse
    {
        if (\str_ends_with($route, '_index') && [] === $parameters) {
            $parameters = $this->filterStorage?->all() ?? [];
        }

        return new RedirectResponse($this->router->generate($route, $parameters));
    }

    private function getRouteArguments(mixed $data, HttpOperation $operation): array
    {
        $resource = $operation->getResource();

        if (null === $resource) {
            throw new \RuntimeException(sprintf('Operation "%s" has no resource, but it should.', $operation->getName() ?? ''));
        }

        $redirectArguments = $operation->getRedirectArguments() ?? [];

        if (
            [] === $redirectArguments &&
            !$operation instanceof DeleteOperationInterface &&
            !$operation instanceof BulkOperationInterface
        ) {
            $identifier = $resource->getIdentifier() ?? 'id';

            $redirectArguments[$identifier] = 'resource.' . $identifier;
        }

        return $this->parseResourceValues($resource, $redirectArguments, $data);
    }

    private function parseResourceValues(ResourceMetadata $resource, array $parameters, mixed $data): array
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($parameters as $key => $value) {
            if (\is_array($value)) {
                $parameters[$key] = $this->parseResourceValues($resource, $value, $data);

                continue;
            }

            if (!\is_scalar($value)) {
                throw new InvalidArgumentException(sprintf('Parameter "%s" should be a scalar or an array.', $key));
            }

            if (\is_string($value) && str_starts_with($value, 'resource.')) {
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

            $parameters[$key] = \is_string($value) ? $this->parseStringValue($value, $variables) : $value;
        }

        return $parameters;
    }

    /**
     * @param array<string, mixed> $variables
     */
    private function parseStringValue(string $value, array $variables): mixed
    {
        if (!str_starts_with($value, '@=')) {
            $value = '@=' . $value;
            trigger_deprecation('sylius/resource-bundle', '1.14', 'You passed "%s" as a string value in your redirect arguments. If this is a value that needs to be parsed using the expression language, please prefix your string with "@=". In your case, use "@=%s"."', $value, $value);
        }

        // Not reachable as long as the BC layer above is there
        if (!str_starts_with($value, '@=')) {
            return $value;
        }

        $value = substr($value, 2);

        return $this->argumentParser->parseExpression($value, $variables);
    }
}
