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
use Sylius\Resource\Metadata\OperationAccessCheckerInterface;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @experimental
 */
final class SecurityProvider implements ProviderInterface
{
    public function __construct(
        private readonly ProviderInterface $provider,
        private readonly OperationAccessCheckerInterface $operationAccessChecker,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        $data = $this->provider->provide($operation, $context);

        if ($this->operationAccessChecker->isGranted($operation, $context, ['object' => $data])) {
            return $data;
        }

        $exception = new AccessDeniedException($operation->getSecurityMessage() ?? 'Access denied.');
        $exception->setAttributes([
            'operation' => $operation,
            'context' => $context,
        ]);
        $exception->setSubject($data);

        throw $exception;
    }
}
