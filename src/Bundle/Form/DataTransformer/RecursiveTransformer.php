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

namespace Sylius\Bundle\ResourceBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class RecursiveTransformer implements DataTransformerInterface
{
    private DataTransformerInterface $decoratedTransformer;

    public function __construct(DataTransformerInterface $decoratedTransformer)
    {
        $this->decoratedTransformer = $decoratedTransformer;
    }

    public function transform($value): Collection
    {
        if (null === $value) {
            return new ArrayCollection();
        }

        $this->assertTransformationValueType($value, Collection::class);

        return $value->map(
            /**
             * @param mixed $currentValue
             *
             * @return mixed
             */
            function ($currentValue) {
                return $this->decoratedTransformer->transform($currentValue);
            },
        );
    }

    public function reverseTransform($value): Collection
    {
        if (null === $value) {
            return new ArrayCollection();
        }

        $this->assertTransformationValueType($value, Collection::class);

        return $value->map(
            /**
             * @param mixed $currentValue
             *
             * @return mixed
             */
            function ($currentValue) {
                return $this->decoratedTransformer->reverseTransform($currentValue);
            },
        );
    }

    /**
     * @param mixed $value
     *
     * @throws TransformationFailedException
     */
    private function assertTransformationValueType($value, string $expectedType): void
    {
        if (!($value instanceof $expectedType)) {
            throw new TransformationFailedException(
                sprintf(
                    'Expected "%s", but got "%s"',
                    $expectedType,
                    is_object($value) ? get_class($value) : gettype($value),
                ),
            );
        }
    }
}
