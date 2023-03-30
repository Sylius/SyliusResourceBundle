<?php

declare(strict_types=1);

namespace Sylius\Component\Resource\State;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
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
        if ($data instanceof Response) {
            return $data;
        }

        $newData = $this->decorated->process($data, $operation, $context);

        if ($newData instanceof Response) {
            return $newData;
        }

        $response = $this->responder->respond($data, $operation, $context);
        Assert::isInstanceOf($response, Response::class);

        return $response;
    }
}
