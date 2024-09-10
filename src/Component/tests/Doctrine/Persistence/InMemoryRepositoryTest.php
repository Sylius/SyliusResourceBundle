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
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Doctrine\Persistence\Exception\ResourceExistsException;
use Sylius\Resource\Doctrine\Persistence\InMemoryRepository;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Exception\UnexpectedTypeException;
use Sylius\Resource\Model\ResourceInterface;

final class InMemoryRepositoryTest extends TestCase
{
    use ProphecyTrait;

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
        /** @var ObjectProphecy<ResourceInterface> $resource */
        $resource = $this->prophesize(ResourceInterface::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->repository->add($resource->reveal());
    }

    public function testItAddsAnObject(): void
    {
        /** @var ObjectProphecy<SampleBookResourceInterface> $monocle */
        $monocle = $this->prophesize(SampleBookResourceInterface::class);

        $monocle->getId()->willReturn(2);
        $this->repository->add($monocle->reveal());
        $this->assertSame($monocle->reveal(), $this->repository->findOneBy(['id' => 2]));
    }

    public function testItThrowsExistingResourceExceptionOnAddingAResourceWhichIsAlreadyInRepository(): void
    {
        /** @var ObjectProphecy<SampleBookResourceInterface> $bike */
        $bike = $this->prophesize(SampleBookResourceInterface::class);

        $this->repository->add($bike->reveal());
        $this->expectException(ResourceExistsException::class);
        $this->repository->add($bike->reveal());
    }

    public function testItRemovesAResource(): void
    {
        /** @var ObjectProphecy<SampleBookResourceInterface> $shirt */
        $shirt = $this->prophesize(SampleBookResourceInterface::class);
        $shirt->getId()->willReturn(5);

        $this->repository->add($shirt->reveal());
        $this->repository->remove($shirt->reveal());

        $this->assertNull($this->repository->findOneBy(['id' => 5]));
    }

    public function testItFindsObjectById(): void
    {
        /** @var ObjectProphecy<SampleBookResourceInterface> $monocle */
        $monocle = $this->prophesize(SampleBookResourceInterface::class);
        $monocle->getId()->willReturn(2);

        $this->repository->add($monocle->reveal());
        $this->assertSame($monocle->reveal(), $this->repository->find(2));
    }

    public function testItReturnsNullIfCannotFindObjectById(): void
    {
        $this->assertNull($this->repository->find(2));
    }

    public function testItReturnsAllObjectsWhenFindingByAnEmptyParameterArray(): void
    {
        /** @var ObjectProphecy<SampleBookResourceInterface> $book */
        $book = $this->prophesize(SampleBookResourceInterface::class);

        /** @var ObjectProphecy<SampleBookResourceInterface> $shirt */
        $shirt = $this->prophesize(SampleBookResourceInterface::class);

        $book->getId()->willReturn(10);
        $book->getName()->willReturn('Book');

        $shirt->getId()->willReturn(5);
        $shirt->getName()->willReturn('Shirt');

        $this->repository->add($book->reveal());
        $this->repository->add($shirt->reveal());

        $this->assertSame([$book->reveal(), $shirt->reveal()], $this->repository->findBy([]));
    }

    public function testItFindsManyObjectsByMultipleCriteriaOrdersALimitAndAnOffset(): void
    {
        /** @var ObjectProphecy<SampleBookResourceInterface> $firstBook */
        $firstBook = $this->prophesize(SampleBookResourceInterface::class);

        /** @var ObjectProphecy<SampleBookResourceInterface> $secondBook */
        $secondBook = $this->prophesize(SampleBookResourceInterface::class);

        /** @var ObjectProphecy<SampleBookResourceInterface> $thirdBook */
        $thirdBook = $this->prophesize(SampleBookResourceInterface::class);

        /** @var ObjectProphecy<SampleBookResourceInterface> $fourthBook */
        $fourthBook = $this->prophesize(SampleBookResourceInterface::class);

        /** @var ObjectProphecy<SampleBookResourceInterface> $wrongIdBook */
        $wrongIdBook = $this->prophesize(SampleBookResourceInterface::class);

        /** @var ObjectProphecy<SampleBookResourceInterface> $wrongNameBook */
        $wrongNameBook = $this->prophesize(SampleBookResourceInterface::class);

        $id = 80;
        $name = 'Book';

        $firstBook->getId()->willReturn($id);
        $secondBook->getId()->willReturn($id);
        $thirdBook->getId()->willReturn($id);
        $fourthBook->getId()->willReturn($id);
        $wrongNameBook->getId()->willReturn($id);
        $wrongIdBook->getId()->willReturn(100);

        $firstBook->getName()->willReturn($name);
        $secondBook->getName()->willReturn($name);
        $thirdBook->getName()->willReturn($name);
        $fourthBook->getName()->willReturn($name);
        $wrongIdBook->getName()->willReturn($name);
        $wrongNameBook->getName()->willReturn('Tome');

        $firstBook->getRating()->willReturn(3);
        $secondBook->getRating()->willReturn(2);
        $thirdBook->getRating()->willReturn(2);
        $fourthBook->getRating()->willReturn(4);

        $firstBook->getTitle()->willReturn('World War Z');
        $secondBook->getTitle()->willReturn('World War Z');
        $thirdBook->getTitle()->willReturn('Call of Cthulhu');
        $fourthBook->getTitle()->willReturn('Art of War');

        $this->repository->add($firstBook->reveal());
        $this->repository->add($secondBook->reveal());
        $this->repository->add($thirdBook->reveal());
        $this->repository->add($fourthBook->reveal());
        $this->repository->add($wrongIdBook->reveal());
        $this->repository->add($wrongNameBook->reveal());

        $this->assertSame(
            [$thirdBook->reveal(), $firstBook->reveal()],
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
        $book = $this->prophesize(SampleBookResourceInterface::class);
        $shirt = $this->prophesize(SampleBookResourceInterface::class);

        $book->getName()->willReturn('Book');
        $shirt->getName()->willReturn('Shirt');

        $this->repository->add($book->reveal());
        $this->repository->add($shirt->reveal());

        $this->assertSame($book->reveal(), $this->repository->findOneBy(['name' => 'Book']));
    }

    public function testItReturnsFirstResultWhileFindingOneByParameters(): void
    {
        $book = $this->prophesize(SampleBookResourceInterface::class);
        $secondBook = $this->prophesize(SampleBookResourceInterface::class);

        $book->getName()->willReturn('Book');
        $secondBook->getName()->willReturn('Book');

        $this->repository->add($book->reveal());
        $this->repository->add($secondBook->reveal());

        $this->assertSame($book->reveal(), $this->repository->findOneBy(['name' => 'Book']));
    }

    public function testItFindsAllObjectsInMemory(): void
    {
        $book = $this->prophesize(SampleBookResourceInterface::class);
        $shirt = $this->prophesize(SampleBookResourceInterface::class);

        $this->repository->add($book->reveal());
        $this->repository->add($shirt->reveal());

        $this->assertSame([$book->reveal(), $shirt->reveal()], $this->repository->findAll());
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
    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getRating();

    /**
     * @return string
     */
    public function getTitle();
}
