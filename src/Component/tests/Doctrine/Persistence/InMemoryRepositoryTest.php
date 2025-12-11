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

namespace Sylius\Resource\Tests\Doctrine\Persistence;

use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Doctrine\Persistence\Exception\ResourceExistsException;
use Sylius\Resource\Doctrine\Persistence\InMemoryRepository;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Exception\UnexpectedTypeException;
use Sylius\Resource\Model\ResourceInterface;

final class InMemoryRepositoryTest extends TestCase
{
    private InMemoryRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryRepository(SampleBookResourceInterface::class);
    }

    public function testItThrowsUnexpectedTypeExceptionWhenConstructingWithoutResourceInterface(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        new InMemoryRepository(\stdClass::class);
    }

    public function testItImplementsRepositoryInterface(): void
    {
        $this->assertInstanceOf(RepositoryInterface::class, $this->repository);
    }

    public function testItThrowsInvalidArgumentExceptionWhenAddingWrongResourceType(): void
    {
        /** @var MockObject<ResourceInterface> $resource */
        $resource = $this->createMock(ResourceInterface::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->repository->add($resource);
    }

    public function testItAddsAnObject(): void
    {
        /** @var MockObject<SampleBookResourceInterface> $monocle */
        $monocle = $this->createMock(SampleBookResourceInterface::class);

        $monocle->method('getId')->willReturn(2);
        $this->repository->add($monocle);
        $this->assertSame($monocle, $this->repository->findOneBy(['id' => 2]));
    }

    public function testItThrowsExistingResourceExceptionOnAddingAResourceWhichIsAlreadyInRepository(): void
    {
        /** @var MockObject<SampleBookResourceInterface> $bike */
        $bike = $this->createMock(SampleBookResourceInterface::class);

        $this->repository->add($bike);
        $this->expectException(ResourceExistsException::class);
        $this->repository->add($bike);
    }

    public function testItRemovesAResource(): void
    {
        /** @var MockObject<SampleBookResourceInterface> $shirt */
        $shirt = $this->createMock(SampleBookResourceInterface::class);
        $shirt->method('getId')->willReturn(5);

        $this->repository->add($shirt);
        $this->repository->remove($shirt);

        $this->assertNull($this->repository->findOneBy(['id' => 5]));
    }

    public function testItFindsObjectById(): void
    {
        /** @var MockObject<SampleBookResourceInterface> $monocle */
        $monocle = $this->createMock(SampleBookResourceInterface::class);
        $monocle->method('getId')->willReturn(2);

        $this->repository->add($monocle);
        $this->assertSame($monocle, $this->repository->find(2));
    }

    public function testItReturnsNullIfCannotFindObjectById(): void
    {
        $this->assertNull($this->repository->find(2));
    }

    public function testItReturnsAllObjectsWhenFindingByAnEmptyParameterArray(): void
    {
        /** @var MockObject<SampleBookResourceInterface> $book */
        $book = $this->createMock(SampleBookResourceInterface::class);

        /** @var MockObject<SampleBookResourceInterface> $shirt */
        $shirt = $this->createMock(SampleBookResourceInterface::class);

        $book->method('getId')->willReturn(10);
        $book->method('getName')->willReturn('Book');

        $shirt->method('getId')->willReturn(5);
        $shirt->method('getName')->willReturn('Shirt');

        $this->repository->add($book);
        $this->repository->add($shirt);

        $this->assertSame([$book, $shirt], $this->repository->findBy([]));
    }

    public function testItFindsManyObjectsByMultipleCriteriaOrdersALimitAndAnOffset(): void
    {
        /** @var MockObject<SampleBookResourceInterface> $firstBook */
        $firstBook = $this->createMock(SampleBookResourceInterface::class);

        /** @var MockObject<SampleBookResourceInterface> $secondBook */
        $secondBook = $this->createMock(SampleBookResourceInterface::class);

        /** @var MockObject<SampleBookResourceInterface> $thirdBook */
        $thirdBook = $this->createMock(SampleBookResourceInterface::class);

        /** @var MockObject<SampleBookResourceInterface> $fourthBook */
        $fourthBook = $this->createMock(SampleBookResourceInterface::class);

        /** @var MockObject<SampleBookResourceInterface> $wrongIdBook */
        $wrongIdBook = $this->createMock(SampleBookResourceInterface::class);

        /** @var MockObject<SampleBookResourceInterface> $wrongNameBook */
        $wrongNameBook = $this->createMock(SampleBookResourceInterface::class);

        $id = 80;
        $name = 'Book';

        $firstBook->method('getId')->willReturn($id);
        $secondBook->method('getId')->willReturn($id);
        $thirdBook->method('getId')->willReturn($id);
        $fourthBook->method('getId')->willReturn($id);
        $wrongNameBook->method('getId')->willReturn($id);
        $wrongIdBook->method('getId')->willReturn(100);

        $firstBook->method('getName')->willReturn($name);
        $secondBook->method('getName')->willReturn($name);
        $thirdBook->method('getName')->willReturn($name);
        $fourthBook->method('getName')->willReturn($name);
        $wrongIdBook->method('getName')->willReturn($name);
        $wrongNameBook->method('getName')->willReturn('Tome');

        $firstBook->method('getRating')->willReturn(3);
        $secondBook->method('getRating')->willReturn(2);
        $thirdBook->method('getRating')->willReturn(2);
        $fourthBook->method('getRating')->willReturn(4);

        $firstBook->method('getTitle')->willReturn('World War Z');
        $secondBook->method('getTitle')->willReturn('World War Z');
        $thirdBook->method('getTitle')->willReturn('Call of Cthulhu');
        $fourthBook->method('getTitle')->willReturn('Art of War');

        $this->repository->add($firstBook);
        $this->repository->add($secondBook);
        $this->repository->add($thirdBook);
        $this->repository->add($fourthBook);
        $this->repository->add($wrongIdBook);
        $this->repository->add($wrongNameBook);

        $this->assertSame(
            [$thirdBook, $firstBook],
            $this->repository->findBy(
                ['name' => $name, 'id' => $id],
                ['rating' => RepositoryInterface::ORDER_ASCENDING, 'title' => RepositoryInterface::ORDER_DESCENDING],
                2,
                1,
            ),
        );
    }

    public function testItThrowsInvalidArgumentExceptionWhenFindingOneObjectWithEmptyParameterArray(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->findOneBy([]);
    }

    public function testItFindsOneObjectByParameter(): void
    {
        $book = new SampleBookResource();
        $book->name = 'Book';

        $shirt = new SampleBookResource();
        $shirt->name = 'Shirt';

        $this->repository->add($book);
        $this->repository->add($shirt);

        $this->assertSame($book, $this->repository->findOneBy(['name' => 'Book']));
    }

    public function testItReturnsFirstResultWhileFindingOneByParameters(): void
    {
        $book = new SampleBookResource();
        $book->name = 'Book';

        $secondBook = new SampleBookResource();
        $secondBook->name = 'Book';

        $this->repository->add($book);
        $this->repository->add($secondBook);

        $this->assertSame($book, $this->repository->findOneBy(['name' => 'Book']));
    }

    public function testItFindsAllObjectsInMemory(): void
    {
        $book = $this->createMock(SampleBookResourceInterface::class);
        $shirt = $this->createMock(SampleBookResourceInterface::class);

        $this->repository->add($book);
        $this->repository->add($shirt);

        $this->assertSame([$book, $shirt], $this->repository->findAll());
    }

    public function testItReturnsEmptyArrayWhenMemoryIsEmpty(): void
    {
        $this->assertSame([], $this->repository->findAll());
    }

    public function testItCreatesPaginator(): void
    {
        $this->assertInstanceOf(Pagerfanta::class, $this->repository->createPaginator());
    }

    public function testItReturnsStatedClassName(): void
    {
        $this->assertSame(SampleBookResourceInterface::class, $this->repository->getClassName());
    }
}

interface SampleBookResourceInterface extends ResourceInterface
{
    public function getName(): string;

    public function getRating(): int;

    public function getTitle(): string;
}

class SampleBookResource implements SampleBookResourceInterface
{
    public $id;

    public $name;

    public $rating;

    public $title;

    public function getId(): mixed
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
