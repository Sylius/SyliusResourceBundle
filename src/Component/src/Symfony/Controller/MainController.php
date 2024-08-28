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

namespace Sylius\Resource\Symfony\Controller;

use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Exception\RuntimeException;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @experimental
 */
final class MainController
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private RequestContextInitiatorInterface $requestContextInitiator,
        private ProviderInterface $provider,
        private ProcessorInterface $processor,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $operation = $this->operationInitiator->initializeOperation($request);

        if (null === $operation) {
            throw new RuntimeException('Operation should not be null.');
        }

        $context = $this->requestContextInitiator->initializeContext($request);

        if (null === $operation->canWrite()) {
            $operation = $operation->withWrite(!$request->isMethodSafe());
        }

        $data = $this->provider->provide($operation, $context);

        $valid = $request->attributes->getBoolean('is_valid', true);
        if (!$valid) {
            $operation = $operation->withWrite(false);
        }

        return $this->processor->process($data, $operation, $context);
    }
}
