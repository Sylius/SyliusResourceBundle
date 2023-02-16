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

namespace Sylius\Component\Resource\Symfony\EventListener;

use App\Subscription\Entity\Subscription;
use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiator;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\SerializerInterface;

final class DeserializeListener
{
    public function __construct(
        private HttpOperationInitiator $operationInitiator,
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

        // TODO use $request->getRequestFormat();
        /** @var string $format */
        $format = $request->attributes->get('_format');

        if (
            null === $operation ||
            'html' === $format ||
            $request->isMethodSafe() ||
            $operation instanceof DeleteOperationInterface
        ) {
            return;
        }

        if (null === $this->serializer) {
            throw new \LogicException(sprintf('You can not use the "%s" format if the Serializer is not available. Try running "composer require symfony/serializer".', $format));
        }

        $data = $this->serializer->deserialize(data: $request->getContent(), type: Subscription::class, format: $format);

        $request->attributes->set('data', $data);
    }
}
