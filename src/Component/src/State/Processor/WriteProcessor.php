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

namespace Sylius\Component\Resource\src\State\Processor;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\Response;

final class WriteProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decorated,
        private ProcessorInterface $processor,
    ) {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        $data = $this->decorated->process($data, $operation, $context);

        $request = $context->get(RequestOption::class)?->request();

        if (
            null === $request ||
            $data instanceof Response ||
            !($operation->canWrite() ?? true) ||
            $request->isMethodSafe() ||
            !$request->attributes->getBoolean('is_valid', true)
        ) {
            return $data;
        }

        switch ($request->getMethod()) {
            case 'PUT':
            case 'PATCH':
            case 'POST':
                $persistResult = $this->processor->process($data, $operation, $context);

                if (!$persistResult) {
                    return $data;
                }

                return $persistResult;
            case 'DELETE':
                $this->processor->process($data, $operation, $context);

                return null;
        }

        return $data;
    }
}
