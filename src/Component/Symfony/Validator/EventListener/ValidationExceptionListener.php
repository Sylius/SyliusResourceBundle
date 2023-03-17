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

namespace Sylius\Component\Resource\Symfony\Validator\EventListener;

use Sylius\Component\Resource\Symfony\Validator\Exception\ConstraintViolationListAwareExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Handles validation errors.
 */
final class ValidationExceptionListener
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    /**
     * Returns a list of violations normalized in the Hydra format.
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ConstraintViolationListAwareExceptionInterface) {
            return;
        }

        $request = $event->getRequest();

        /** @var string $format */
        $format = $request->getRequestFormat();

        /** @var string $mimeType */
        $mimeType = $request->getMimeType($format);

        $event->setResponse(new Response(
            $this->serializer->serialize($exception->getConstraintViolationList(), $format),
            Response::HTTP_UNPROCESSABLE_ENTITY,
            [
                'Content-Type' => sprintf('%s; charset=utf-8', $mimeType),
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'deny',
            ],
        ));
    }
}
