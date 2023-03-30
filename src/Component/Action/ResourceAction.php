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

namespace Sylius\Component\Resource\Action;

use Sylius\Component\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final class ResourceAction
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private RequestContextInitiatorInterface $requestContextInitiator,
        private ProcessorInterface $processor,
    ) {
    }

    public function __invoke(Request $request, $data = null): mixed
    {
        $operation = $this->operationInitiator->initializeOperation($request);
        Assert::notNull($operation);

        $context = $this->requestContextInitiator->initializeContext($request);

        return $this->processor->process($data, $operation, $context);
    }
}
