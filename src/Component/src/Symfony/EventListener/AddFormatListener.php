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

use Negotiation\BaseAccept;
use Negotiation\Negotiator;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

/**
 * @experimental
 */
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

        if (
            null === $operation
        ) {
            return;
        }

        $mimeTypes = ['text/html', 'application/json', 'application/xml'];

        // First, try to guess the format from the Accept header
        $accept = $request->headers->get('Accept');
        if (null !== $accept) {
            /** @var BaseAccept|null $mediaType */
            $mediaType = $this->negotiator->getBest($accept, $mimeTypes);

            if (null !== $mediaType) {
                $request->setRequestFormat($request->getFormat($mediaType->getType()));

                return;
            }
        }

        // Then use the Symfony request format if available and applicable
        $requestFormat = $request->getRequestFormat(null);
        if (null !== $requestFormat) {
            $mimeType = $request->getMimeType($requestFormat);

            if (\in_array($mimeType, $mimeTypes, true)) {
                return;
            }

            throw $this->getNotAcceptableHttpException($mimeType ?? '', $mimeTypes);
        }
    }

    /**
     * Retrieves an instance of NotAcceptableHttpException.
     */
    private function getNotAcceptableHttpException(string $accept, array $mimeTypes): NotAcceptableHttpException
    {
        return new NotAcceptableHttpException(sprintf(
            'Requested format "%s" is not supported. Supported MIME types are "%s".',
            $accept,
            implode('", "', $mimeTypes),
        ));
    }
}
