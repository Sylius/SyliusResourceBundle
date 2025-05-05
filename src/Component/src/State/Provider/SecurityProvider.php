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

namespace Sylius\Resource\State\Provider;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @experimental
 */
final class SecurityProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $provider,
        private readonly SecurityAttributeProviderInterface $securityAttributeProvider,
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        $data = $this->provider->provide($operation, $context);

        $attribute = $this->securityAttributeProvider->getAttribute($operation, $context);

        if (!$this->authorizationChecker->isGranted($attribute, $data)) {
            $exception = new AccessDeniedException();
            $exception->setAttributes($attribute);
            $exception->setSubject($data);

            throw $exception;
        }

        return $data;
    }
}
