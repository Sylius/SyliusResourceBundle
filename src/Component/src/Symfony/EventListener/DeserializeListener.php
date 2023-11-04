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

namespace Sylius\Resource\Symfony\EventListener;

use Sylius\Resource\Metadata\DeleteOperationInterface;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class DeserializeListener
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private ?SerializerInterface $serializer,
    ) {
    }

    /**
     * Deserializes the data sent in the requested format.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $operation = $this->operationInitiator->initializeOperation($request);

        /** @var string $format */
        $format = $request->getRequestFormat();

        $resourceClass = $operation?->getResource()?->getClass();

        if (
            null === $operation ||
            null === $resourceClass ||
            'html' === $format ||
            $request->isMethodSafe() ||
            $operation instanceof DeleteOperationInterface ||
            !($operation->canDeserialize() ?? true)
        ) {
            return;
        }

        if (null === $this->serializer) {
            throw new \LogicException(sprintf('You can not use the "%s" format if the Serializer is not available. Try running "composer require symfony/serializer".', $format));
        }

        $data = $request->attributes->get('data');

        $denormalizationContext = $operation->getDenormalizationContext() ?? [];

        if (null !== $data) {
            $denormalizationContext[AbstractNormalizer::OBJECT_TO_POPULATE] = $data;
        }

        $data = $this->serializer->deserialize($request->getContent(), $resourceClass, $format, $denormalizationContext);

        $request->attributes->set('data', $data);
    }
}
