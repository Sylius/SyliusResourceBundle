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

namespace Sylius\Component\Resource\State;

use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Webmozart\Assert\Assert;

final class Responder implements ResponderInterface
{
    public function __construct(private ContainerInterface $locator)
    {
    }

    public function respond(mixed $data, Operation $operation, Context $context): mixed
    {
        $responder = $operation->getResponder();

        if (null === $responder) {
            return null;
        }

        if (\is_callable($responder)) {
            return $responder($data, $operation, $context);
        }

        if (!$this->locator->has($responder)) {
            throw new \RuntimeException(sprintf('Responder "%s" not found on operation "%s"', $responder, $operation->getName() ?? ''));
        }

        $responderInstance = $this->locator->get($responder);
        Assert::isInstanceOf($responderInstance, ResponderInterface::class);

        return $responderInstance->respond($data, $operation, $context);
    }
}
