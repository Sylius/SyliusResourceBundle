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

namespace Sylius\Component\Resource\State;

use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;

final class CallableResponder implements ResponderInterface
{
    public function __construct(private ContainerInterface $locator)
    {
    }

    public function respond(mixed $data, Operation $operation, Context $context): mixed
    {
        if (\is_callable($responder = $operation->getResponder())) {
            return $responder($data, $operation, $context);
        }

        if (\is_string($responder)) {
            if (!$this->locator->has($responder)) {
                throw new \RuntimeException(sprintf('Responder "%s" not found on operation "%s"', $responder, $operation->getName()));
            }

            /** @var ResponderInterface $providerInstance */
            $responderInstance = $this->locator->get($responder);

            return $responderInstance->provide($data, $operation, $context);
        }

        throw new \RuntimeException(sprintf('Responder not found on operation "%s"', $operation->getName()));
    }
}
