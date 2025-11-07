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

namespace Sylius\Bundle\ResourceBundle\Tests\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\RecursiveTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class RecursiveTransformerTest extends TestCase
{
    private DataTransformerInterface $decoratedTransformer;

    private RecursiveTransformer $transformer;

    protected function setUp(): void
    {
        $this->decoratedTransformer = $this->createMock(DataTransformerInterface::class);
        $this->transformer = new RecursiveTransformer($this->decoratedTransformer);
    }

    public function testImplementsDataTransformerInterface(): void
    {
        self::assertInstanceOf(DataTransformerInterface::class, $this->transformer);
    }

    public function testReturnsEmptyCollectionWhenTransformingNull(): void
    {
        $this->decoratedTransformer
            ->expects(self::never())
            ->method('transform');

        $result = $this->transformer->transform(null);

        self::assertEquals(new ArrayCollection(), $result);
    }

    public function testReturnsEmptyCollectionWhenReverseTransformingNull(): void
    {
        $this->decoratedTransformer
            ->expects(self::never())
            ->method('reverseTransform');

        $result = $this->transformer->reverseTransform(null);

        self::assertEquals(new ArrayCollection(), $result);
    }

    public function testTransformsCollectionRecursively(): void
    {
        $this->decoratedTransformer
            ->expects(self::exactly(3))
            ->method('transform')
            ->willReturnCallback(fn (string $value): string => strtolower($value));

        $result = $this->transformer->transform(new ArrayCollection(['ABC', 'CDE', 'FGH']));

        self::assertEquals(new ArrayCollection(['abc', 'cde', 'fgh']), $result);
    }

    public function testReverseTransformsCollectionRecursively(): void
    {
        $this->decoratedTransformer
            ->expects(self::exactly(3))
            ->method('reverseTransform')
            ->willReturnCallback(fn (string $value): string => strtoupper($value));

        $result = $this->transformer->reverseTransform(new ArrayCollection(['abc', 'cde', 'fgh']));

        self::assertEquals(new ArrayCollection(['ABC', 'CDE', 'FGH']), $result);
    }

    public function testThrowsExceptionWhenTransformingNonCollection(): void
    {
        self::expectException(TransformationFailedException::class);
        self::expectExceptionMessage('Expected "Doctrine\Common\Collections\Collection", but got "stdClass"');

        $this->transformer->transform(new \stdClass());
    }

    public function testThrowsExceptionWhenReverseTransformingNonCollection(): void
    {
        self::expectException(TransformationFailedException::class);
        self::expectExceptionMessage('Expected "Doctrine\Common\Collections\Collection", but got "stdClass"');

        $this->transformer->reverseTransform(new \stdClass());
    }
}
