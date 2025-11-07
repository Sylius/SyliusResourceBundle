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

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class ResourceToIdentifierTransformerTest extends TestCase
{
    private RepositoryInterface $repository;

    private ResourceToIdentifierTransformer $transformer;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(RepositoryInterface::class);
        $this->transformer = new ResourceToIdentifierTransformer($this->repository, 'id');
    }

    public function testImplementsDataTransformerInterface(): void
    {
        self::assertInstanceOf(DataTransformerInterface::class, $this->transformer);
    }

    public function testTransformsNullValueToNull(): void
    {
        $result = $this->transformer->transform(null);

        self::assertNull($result);
    }

    public function testTransformsResourceToIdentifier(): void
    {
        $resource = $this->createMock(ResourceInterface::class);
        $resource
            ->expects(self::once())
            ->method('getId')
            ->willReturn(6);

        $this->repository
            ->expects(self::once())
            ->method('getClassName')
            ->willReturn(ResourceInterface::class);

        $result = $this->transformer->transform($resource);

        self::assertSame(6, $result);
    }

    public function testReverseTransformsNullToNull(): void
    {
        $result = $this->transformer->reverseTransform(null);

        self::assertNull($result);
    }

    public function testReverseTransformsIdentifierToResource(): void
    {
        $resource = $this->createMock(ResourceInterface::class);

        $this->repository
            ->expects(self::once())
            ->method('findOneBy')
            ->with(['id' => 5])
            ->willReturn($resource);

        $result = $this->transformer->reverseTransform(5);

        self::assertSame($resource, $result);
    }

    public function testThrowsExceptionWhenResourceDoesNotExist(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('findOneBy')
            ->with(['id' => 6])
            ->willReturn(null);

        $this->repository
            ->expects(self::once())
            ->method('getClassName')
            ->willReturn(ResourceInterface::class);

        self::expectException(TransformationFailedException::class);
        self::expectExceptionMessage('Object "Sylius\Resource\Model\ResourceInterface" with identifier "id"="6" does not exist.');

        $this->transformer->reverseTransform(6);
    }
}
