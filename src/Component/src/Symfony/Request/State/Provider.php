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

namespace Sylius\Resource\Symfony\Request\State;

use Pagerfanta\Pagerfanta;
use Psr\Container\ContainerInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\BulkOperationInterface;
use Sylius\Resource\Metadata\CollectionOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Reflection\CallableReflection;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;
use Sylius\Resource\Symfony\Request\RepositoryArgumentResolver;

final class Provider implements ProviderInterface
{
    public function __construct(
        private ContainerInterface $locator,
        private RepositoryArgumentResolver $argumentResolver,
        private ArgumentParserInterface $argumentParser,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        $request = $context->get(RequestOption::class)?->request();
        $repository = $operation->getRepository();

        if (
            null === $request ||
            null === $repository
        ) {
            return null;
        }

        $repositoryInstance = null;
        $arguments = $this->parseArgumentValues($operation->getRepositoryArguments() ?? []);

        if (\is_string($repository)) {
            $defaultMethod = $operation instanceof CollectionOperationInterface ? 'createPaginator' : 'findOneBy';

            if ($operation instanceof BulkOperationInterface) {
                $defaultMethod = 'findById';
            }

            $method = $operation->getRepositoryMethod() ?? $defaultMethod;

            if (!$this->locator->has($repository)) {
                throw new \RuntimeException(sprintf('Repository "%s" not found on operation "%s"', $repository, $operation->getName() ?? ''));
            }

            $repositoryInstance = $this->locator->get($repository);

            // make it as callable
            /** @var callable $repository */
            $repository = [$repositoryInstance, $method];
        }

        try {
            $reflector = CallableReflection::from($repository);
        } catch (\ReflectionException $exception) {
            if (null === $repositoryInstance) {
                throw $exception;
            }

            /** @var callable $callable */
            $callable = [$repositoryInstance, '__call'];

            $reflector = CallableReflection::from($callable);
        }

        if ([] === $arguments) {
            $arguments = $this->argumentResolver->getArguments($request, $reflector);
        }

        $data = $repository(...$arguments);

        if ($data instanceof Pagerfanta) {
            $currentPage = $request->query->getInt('page', 1);
            $data->setCurrentPage($currentPage);
        }

        return $data;
    }

    private function parseArgumentValues(array $arguments): array
    {
        foreach ($arguments as $key => $value) {
            $arguments[$key] = $this->argumentParser->parseExpression($value);
        }

        return $arguments;
    }
}
