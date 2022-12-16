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

namespace Sylius\Component\Resource\Doctrine\ORM\State\Http;

use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\MetadataOption;
use Sylius\Component\Resource\Context\Option\RequestConfigurationOption;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\State\ProviderInterface;
use function Sylius\Component\Resource\Doctrine\ORM\State\count;

final class ItemProvider implements ProviderInterface
{
    public function __construct(private ContainerInterface $repositoryLocator)
    {
    }

    public function provide(Operation $operation, Context $context): ?ResourceInterface
    {
        $metadata = $context->get(MetadataOption::class)->metadata();

        if (null === $metadata) {
            throw new \RuntimeException('Metadata was not found on the context');
        }

        $repositoryId = $metadata->getServiceId('repository');

        if (!$this->repositoryLocator->has($repositoryId)) {
            throw new \RuntimeException(sprintf('Repository "%s" not found on operation "%s"', $repositoryId, $operation->getName()));
        }

        $repository = $this->repositoryLocator->get($repositoryId);
        $method = $operation->getRepository()['method'] ?? $operation->getRepository();
        $arguments = $operation->getRepository()['arguments'] ?? [];

        if (null !== $method) {
            if (is_array($method) && 2 === count($method)) {
                $repository = $method[0];
                $method = $method[1];
            }

            $arguments = array_values($arguments);

            return $repository->$method(...$arguments);
        }

        $criteria = [];
        $request = $context->get(RequestOption::class)->request();
        $requestConfiguration = $context->get(RequestConfigurationOption::class)->configuration();

        if (null === $request || $requestConfiguration) {
            return null;
        }

        if ($request->attributes->has('id')) {
            /** @var ResourceInterface|null $resource */
            $resource = $repository->find($request->attributes->get('id'));

            return $resource;
        }

        if ($request->attributes->has('slug')) {
            $criteria = ['slug' => $request->attributes->get('slug')];
        }

        $criteria = array_merge($criteria, $requestConfiguration->getCriteria());

        /** @var ResourceInterface|null $resource */
        $resource = $repository->findOneBy($criteria);

        return $resource;
    }
}
