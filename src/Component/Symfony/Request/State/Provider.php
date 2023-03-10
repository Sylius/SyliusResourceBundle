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

namespace Sylius\Component\Resource\Symfony\Request\State;

use Pagerfanta\Pagerfanta;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\BulkOperationInterface;
use Sylius\Component\Resource\Metadata\CollectionOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Reflection\CallableReflection;
use Sylius\Component\Resource\State\ProviderInterface;
use Sylius\Component\Resource\Symfony\Request\RepositoryArgumentResolver;

final class Provider implements ProviderInterface
{
    public function __construct(
        private ContainerInterface $locator,
        private RepositoryArgumentResolver $argumentResolver,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|iterable|null
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

        $arguments = $this->argumentResolver->getArguments($request, $reflector);
        $data = $repository(...$arguments);

        if ($data instanceof Pagerfanta) {
            $currentPage = $request->query->getInt('page', 1);
            $data->setCurrentPage($currentPage);
        }

        return $data;
    }
}
