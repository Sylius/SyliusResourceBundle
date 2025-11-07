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
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\CollectionToStringTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class CollectionToStringTransformerTest extends TestCase
{
    private CollectionToStringTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new CollectionToStringTransformer(',');
    }

    public function testImplementsDataTransformerInterface(): void
    {
        self::assertInstanceOf(DataTransformerInterface::class, $this->transformer);
    }

    public function testTransformsCollectionToString(): void
    {
        $collection = new ArrayCollection(['abc', 'def', 'ghi', 'jkl']);

        $result = $this->transformer->transform($collection);

        self::assertSame('abc,def,ghi,jkl', $result);
    }

    public function testTransformsEmptyCollectionToEmptyString(): void
    {
        $result = $this->transformer->transform(new ArrayCollection());

        self::assertSame('', $result);
    }

    public function testTransformsStringToCollection(): void
    {
        $result = $this->transformer->reverseTransform('abc,def,ghi,jkl');

        self::assertEquals(new ArrayCollection(['abc', 'def', 'ghi', 'jkl']), $result);
    }

    public function testTransformsEmptyStringToEmptyCollection(): void
    {
        $result = $this->transformer->reverseTransform('');

        self::assertEquals(new ArrayCollection(), $result);
    }

    public function testTransformsCollectionWithSingleElement(): void
    {
        $result = $this->transformer->transform(new ArrayCollection(['single']));

        self::assertSame('single', $result);
    }

    public function testReverseTransformsSingleValue(): void
    {
        $result = $this->transformer->reverseTransform('single');

        self::assertEquals(new ArrayCollection(['single']), $result);
    }

    public function testThrowsExceptionWhenTransformingNonCollection(): void
    {
        self::expectException(TransformationFailedException::class);
        self::expectExceptionMessage('Expected "Doctrine\Common\Collections\Collection", but got "stdClass"');

        $this->transformer->transform(new \stdClass());
    }

    public function testThrowsExceptionWhenReverseTransformingNonString(): void
    {
        self::expectException(TransformationFailedException::class);
        self::expectExceptionMessage('Expected string, but got "stdClass"');

        $this->transformer->reverseTransform(new \stdClass());
    }
}
