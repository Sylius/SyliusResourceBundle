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

namespace Sylius\Resource\Doctrine\ORM\State;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\PagerfantaInterface;
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

/**
 * @experimental
 */
final class Provider implements ProviderInterface
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private RepositoryArgumentResolver $argumentResolver,
        private ArgumentParserInterface $argumentParser,
        private ContainerInterface $locator,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return null;
        }

        $repository = $operation->getRepository();
        $repositoryInstance = null;

        if (\is_callable($repository)) {
            $callableRepository = $repository;
        } else {
            $repositoryInstance = $this->getRepositoryInstance($operation);
            $callableRepository = $this->createCallableRepository($operation, $repositoryInstance);
        }

        try {
            $reflector = CallableReflection::from($callableRepository);
        } catch (\ReflectionException $exception) {
            if (null === $repositoryInstance) {
                throw $exception;
            }

            /** @var callable $callable */
            $callable = [$repositoryInstance, '__call'];

            $reflector = CallableReflection::from($callable);
        }

        $arguments = $this->parseArgumentValues($operation->getRepositoryArguments() ?? []);

        if ([] === $arguments) {
            $arguments = $this->argumentResolver->getArguments($request, $reflector);
        }

        $data = $callableRepository(...$arguments);

        if ($data instanceof PagerfantaInterface) {
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

    private function createCallableRepository(Operation $operation, mixed $repositoryInstance): callable
    {
        $defaultMethod = $operation instanceof CollectionOperationInterface ? 'createPaginator' : 'findOneBy';

        if ($operation instanceof BulkOperationInterface) {
            $defaultMethod = 'findById';
        }

        $method = $operation->getRepositoryMethod() ?? $defaultMethod;

        // make it as callable
        /** @var callable $repository */
        $repository = [$repositoryInstance, $method];

        return $repository;
    }

    private function getRepositoryInstance(Operation $operation): mixed
    {
        /** @var string|null $repository */
        $repository = $operation->getRepository();

        if (null === $repository) {
            /** @var class-string $entityClass */
            $entityClass = $operation->getResource()?->getClass();

            /** @var EntityManagerInterface $manager */
            $manager = $this->managerRegistry->getManagerForClass($entityClass);

            return $manager->getRepository($entityClass);
        }

        if (!$this->locator->has($repository)) {
            throw new \RuntimeException(sprintf('Repository "%s" not found on operation "%s"', $repository, $operation->getName() ?? ''));
        }

        return $this->locator->get($repository);
    }
}
