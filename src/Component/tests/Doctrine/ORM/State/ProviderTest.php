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

namespace Sylius\Resource\Tests\Doctrine\ORM\State;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Persisters\Entity\EntityPersister;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Doctrine\ORM\State\Provider;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;
use Sylius\Resource\Symfony\Request\RepositoryArgumentResolver;
use Symfony\Component\HttpFoundation\Request;

final class ProviderTest extends TestCase
{
    private Provider $provider;

    /** @var ManagerRegistry&ObjectProphecy */
    private ManagerRegistry $managerRegistry;

    /** @var ContainerInterface&ObjectProphecy */
    private ContainerInterface $locator;

    /** @var ArgumentParserInterface&ObjectProphecy */
    private ArgumentParserInterface $argumentParser;

    protected function setUp(): void
    {
        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->locator = $this->createMock(ContainerInterface::class);
        $this->argumentParser = $this->createMock(ArgumentParserInterface::class);
        $this->provider = new Provider($this->managerRegistry, new RepositoryArgumentResolver(), $this->argumentParser, $this->locator);
    }

    public function testItCallsRepositoryFromDoctrineManagerRegistry(): void
    {
        $operation = $this->createMock(Operation::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $unitOfWork = $this->createMock(UnitOfWork::class);
        $entityPersister = $this->createMock(EntityPersister::class);

        $operation->method('getRepository')->willReturn(null);
        $operation->method('getRepositoryArguments')->willReturn(null);
        $operation->method('getResource')->willReturn((new ResourceMetadata())->withClass('App\Dummy'));

        $this->managerRegistry->method('getManagerForClass')->with('App\Dummy')->willReturn($entityManager);
        $entityRepository = new EntityRepository($entityManager, new ClassMetadata('App\Dummy'));
        $entityManager->method('getRepository')->willReturn($entityRepository);

        $entityManager->method('getUnitOfWork')->willReturn($unitOfWork);
        $unitOfWork->method('getEntityPersister')->willReturn($entityPersister);

        $expectedResult = (object) ['id' => 'my_id'];
        $entityPersister->method('load')->with(['id' => 'my_id'], null, null, [], null, 1)->willReturn($expectedResult);

        $request = new Request([], [], ['_route_params' => ['id' => 'my_id']]);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));
        $this->assertEquals($expectedResult, $response);
    }

    public function testItCallsRepositoryAsCallable(): void
    {
        $operation = $this->createMock(Operation::class);
        $operation->method('getRepository')->willReturn([RepositoryWithCallables::class, 'find']);
        $operation->method('getRepositoryArguments')->willReturn(null);

        $request = new Request([], [], ['_route_params' => ['id' => 'my_id']]);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));
        $this->assertInstanceOf(\stdClass::class, $response);
        $this->assertEquals('my_id', $response->id);
    }

    public function testItCallsRepositoryAsString(): void
    {
        $operation = $this->createMock(Operation::class);
        $operation->method('getRepository')->willReturn('App\\Repository');
        $operation->method('getRepositoryMethod')->willReturn(null);
        $operation->method('getRepositoryArguments')->willReturn(null);

        $request = new Request([], [], ['_route_params' => ['id' => 'my_id', '_sylius' => ['resource' => 'app.dummy']]]);

        $repository = $this->createMock(RepositoryInterface::class);
        $stdClass = new \stdClass();

        $this->locator->method('has')->with('App\\Repository')->willReturn(true);
        $this->locator->method('get')->with('App\\Repository')->willReturn($repository);
        $repository->method('findOneBy')->with(['id' => 'my_id'])->willReturn($stdClass);

        $response = $this->provider->provide($operation, new Context(new RequestOption($request)));
        $this->assertSame($stdClass, $response);
    }
}
