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

namespace Sylius\Component\Resource\Symfony\Validator\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Thrown when a validation error occurs.
 */
final class ValidationException extends \RuntimeException implements ConstraintViolationListAwareExceptionInterface
{
    public function __construct(private ConstraintViolationListInterface $constraintViolationList, string $message = '', int $code = 0, \Exception $previous = null)
    {
        parent::__construct($message ?: $this->__toString(), $code, $previous);
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }

    public function __toString(): string
    {
        $message = '';
        foreach ($this->constraintViolationList as $violation) {
            if ('' !== $message) {
                $message .= "\n";
            }
            if ($propertyPath = $violation->getPropertyPath()) {
                $message .= "$propertyPath: ";
            }

            $message .= $violation->getMessage();
        }

        return $message;
    }
}
