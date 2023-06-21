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

namespace Sylius\Component\Resource\Symfony\EventDispatcher;

use Symfony\Component\EventDispatcher\GenericEvent as BaseGenericEvent;
use Symfony\Component\HttpFoundation\Response;

class GenericEvent extends BaseGenericEvent
{
    public const TYPE_ERROR = 'error';

    public const TYPE_WARNING = 'warning';

    public const TYPE_INFO = 'info';

    public const TYPE_SUCCESS = 'success';

    private string $messageType = '';

    private string $message = '';

    /** @var array */
    private $messageParameters = [];

    private int $errorCode = 500;

    private ?Response $response = null;

    /**
     * @psalm-suppress MissingReturnType
     */
    public function stop(string $message, string $type = self::TYPE_ERROR, array $parameters = [], int $errorCode = 500)
    {
        $this->messageType = $type;
        $this->message = $message;
        $this->messageParameters = $parameters;
        $this->errorCode = $errorCode;

        $this->stopPropagation();
    }

    public function isStopped(): bool
    {
        return $this->isPropagationStopped();
    }

    public function getMessageType(): string
    {
        return $this->messageType;
    }

    /**
     * @param string $messageType Should be one of ResourceEvent's TYPE constants
     */
    public function setMessageType($messageType): void
    {
        $this->messageType = $messageType;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getMessageParameters(): array
    {
        return $this->messageParameters;
    }

    public function setMessageParameters(array $messageParameters): void
    {
        $this->messageParameters = $messageParameters;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function hasResponse(): bool
    {
        return null !== $this->response;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }
}

\class_alias(GenericEvent::class, \Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent::class);
