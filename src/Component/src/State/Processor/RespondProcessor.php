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
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\State\ResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class RespondProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decorated,
        private ResponderInterface $responder,
    ) {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        $data = $this->decorated->process($data, $operation, $context);

        if ($data instanceof Response) {
            return $data;
        }

        $response = $this->responder->respond($data, $operation, $context);
        Assert::isInstanceOf($response, Response::class);

        return $response;
    }
}
