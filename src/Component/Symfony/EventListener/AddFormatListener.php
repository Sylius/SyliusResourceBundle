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

use Negotiation\BaseAccept;
use Negotiation\Negotiator;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class AddFormatListener
{
    public function __construct(
        private HttpOperationInitiatorInterface $operationInitiator,
        private Negotiator $negotiator,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $operation = $this->operationInitiator->initializeOperation($request);

        if (null === $operation) {
            return;
        }

        /** @var string|null $routeFormat */
        $routeFormat = $request->attributes->has('_format') ? Request::getMimeTypes($request->attributes->get('_format')) : ['application/json', 'application/xml'];
        $accept = $request->headers->get('Accept');

        if (null === $accept) {
            return;
        }

        /** @var BaseAccept|null $mediaType */
        $mediaType = $this->negotiator->getBest($accept, $mimeTypes);

        if (null === $mediaType) {
            return;
        }

        $request->setRequestFormat($request->getFormat($mediaType->getType()));
    }
}
