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

namespace Sylius\Resource\Symfony\Serializer\State;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProcessorInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Serializes the data to the requested format.
 *
 * @experimental
 */
final class SerializeProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private ?SerializerInterface $serializer,
    ) {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return $this->processor->process($data, $operation, $context);
        }

        /** @var string $format */
        $format = $request->getRequestFormat();

        if (
            'html' === $format ||
            !($operation->canSerialize() ?? true)
        ) {
            return $this->processor->process($data, $operation, $context);
        }

        if (null === $this->serializer) {
            throw new \LogicException(sprintf('You can not use the "%s" format if the Serializer is not available. Try running "composer require symfony/serializer".', $format));
        }

        $serialized = $this->serializer->serialize($data, $format, $operation->getNormalizationContext() ?? []);

        return $this->processor->process($serialized, $operation, $context);
    }
}
