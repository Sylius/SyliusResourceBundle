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

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\Operation;
use Symfony\Component\HttpFoundation\Response;

final class WriteProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decorated,
    ) {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        $request = $context->get(RequestOption::class)?->request();

        if (
            null === $request ||
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
                $persistResult = $this->decorated->process($data, $operation, $context);

                if (!$persistResult) {
                    return $data;
                }

                return $persistResult;

                break;
            case 'DELETE':
                $this->decorated->process($data, $operation, $context);

                return $data;
        }

        return $data;
    }
}
