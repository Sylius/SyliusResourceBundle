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

use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiator;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

final class SerializerListener
{
    public function __construct(
        private HttpOperationInitiator $operationInitiator,
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

        // TODO use $request->getRequestFormat();
        /** @var string $format */
        $format = $request->attributes->get('_format');

        if (
            null === $operation ||
            'html' === $format
        ) {
            return;
        }

        if (null === $this->serializer) {
            throw new \LogicException(sprintf('You can not use the "%s" format if the Serializer is not available. Try running "composer require symfony/serializer".', $format));
        }

        $controllerResult = $event->getControllerResult();

        $event->setControllerResult($this->serializer->serialize($controllerResult, $format));
    }
}
