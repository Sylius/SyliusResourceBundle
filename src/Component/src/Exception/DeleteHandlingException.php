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

namespace Sylius\Resource\Exception;

class DeleteHandlingException extends RuntimeException
{
    public function __construct(
        string $message = 'Ups, something went wrong during deleting a resource, please try again.',
        protected string $flash = 'something_went_wrong_error',
        protected int $apiResponseCode = 500,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getFlash(): string
    {
        return $this->flash;
    }

    public function getApiResponseCode(): int
    {
        return $this->apiResponseCode;
    }
}

class_alias(DeleteHandlingException::class, \Sylius\Component\Resource\Exception\DeleteHandlingException::class);
