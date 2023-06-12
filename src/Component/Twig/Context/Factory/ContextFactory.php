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

namespace Sylius\Component\Resource\Twig\Context\Factory;

use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Operation;
use Webmozart\Assert\Assert;

final class ContextFactory implements ContextFactoryInterface
{
    public function __construct(private ContainerInterface $locator)
    {
    }

    public function create(mixed $data, Operation $operation, Context $context): array
    {
        if (
            !$operation instanceof HttpOperation ||
            null === $twigContextFactory = $operation->getTwigContextFactory()
        ) {
            return [];
        }

        if (\is_callable($twigContextFactory)) {
            return $twigContextFactory($data, $operation, $context);
        }

        if (!$this->locator->has($twigContextFactory)) {
            throw new \RuntimeException(sprintf('Twig context factory "%s" not found on operation "%s"', $twigContextFactory, $operation->getName() ?? ''));
        }

        /** @var ContextFactoryInterface $twigContextFactoryInstance */
        $twigContextFactoryInstance = $this->locator->get($twigContextFactory);
        Assert::isInstanceOf($twigContextFactoryInstance, ContextFactoryInterface::class);

        return $twigContextFactoryInstance->create($data, $operation, $context);
    }
}
