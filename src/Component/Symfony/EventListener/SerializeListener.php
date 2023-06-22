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

namespace Sylius\Component\Resource\Symfony\EventListener;

use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

final class SerializeListener
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private ?SerializerInterface $serializer,
    ) {
    }

    /**
     * Serializes the data to the requested format.
     */
    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();
        $operation = $this->operationInitiator->initializeOperation($request);

        /** @var string $format */
        $format = $request->getRequestFormat();

        if (
            null === $operation ||
            'html' === $format ||
            !($operation->canSerialize() ?? true)
        ) {
            return;
        }

        if (null === $this->serializer) {
            throw new \LogicException(sprintf('You can not use the "%s" format if the Serializer is not available. Try running "composer require symfony/serializer".', $format));
        }

        $controllerResult = $event->getControllerResult();

        $event->setControllerResult($this->serializer->serialize($controllerResult, $format, $operation->getNormalizationContext() ?? []));
    }
}
