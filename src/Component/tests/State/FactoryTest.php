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

namespace Sylius\Resource\Tests\State;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Factory\FactoryInterface;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\State\Factory;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;

final class FactoryTest extends TestCase
{
    use ProphecyTrait;

    private Factory $factory;

    private ContainerInterface|ObjectProphecy $locator;

    private ArgumentParserInterface|ObjectProphecy $argumentParser;

    protected function setUp(): void
    {
        $this->locator = $this->prophesize(ContainerInterface::class);
        $this->argumentParser = $this->prophesize(ArgumentParserInterface::class);
        $this->factory = new Factory($this->locator->reveal(), $this->argumentParser->reveal());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(Factory::class, $this->factory);
    }

    public function testItCallsFactoryFromOperationAsCallable(): void
    {
        $operation = new Create(factory: [FactoryCallable::class, 'create']);
        $result = $this->factory->create($operation, new Context());

        $this->assertInstanceOf(\stdClass::class, $result);
    }

    public function testItCallsFactoryWithArgumentsFromOperationAsCallable(): void
    {
        $operation = new Create(factory: [FactoryCallable::class, 'create'], factoryArguments: ['userId' => 'user.getUserIdentifier()']);
        $this->argumentParser->parseExpression('user.getUserIdentifier()')->willReturn('51353e91-5295-4876-a994-cae4b3ff3a7c');

        $result = $this->factory->create($operation, new Context());

        $this->assertInstanceOf(\stdClass::class, $result);
        $this->assertEquals('51353e91-5295-4876-a994-cae4b3ff3a7c', $result->userId);
    }

    public function testItCallsFactoryFromOperationAsString(): void
    {
        $factory = $this->prophesize(FactoryInterface::class);
        $operation = new Create(name: 'app_dummy_create', factory: get_class($factory->reveal()), factoryMethod: 'createNew');
        $data = new \stdClass();

        $this->locator->has(get_class($factory->reveal()))->willReturn(true);
        $this->locator->get(get_class($factory->reveal()))->willReturn($factory->reveal());
        $factory->createNew()->willReturn($data);

        $result = $this->factory->create($operation, new Context());

        $this->assertSame($data, $result);
    }

    public function testItThrowsExceptionWhenFactoryIsNotFoundOnLocator(): void
    {
        $factory = $this->prophesize(FactoryInterface::class);
        $operation = new Create(name: 'app_dummy_create', factory: get_class($factory->reveal()), factoryMethod: 'createNew');

        $this->locator->has(get_class($factory->reveal()))->willReturn(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Factory "%s" not found on operation "app_dummy_create"', get_class($factory->reveal())));

        $this->factory->create($operation, new Context());
    }

    public function testItThrowsExceptionWhenFactoryMethodIsNull(): void
    {
        $factory = $this->prophesize(FactoryInterface::class);
        $operation = new Create(name: 'app_dummy_create', factory: get_class($factory->reveal()));

        $this->locator->has(get_class($factory->reveal()))->willReturn(true);
        $this->locator->get(get_class($factory->reveal()))->willReturn($factory->reveal());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No Factory method was configured on operation "app_dummy_create"');

        $this->factory->create($operation, new Context());
    }
}

final class FactoryCallable
{
    public static function create(?string $userId = null): object
    {
        $object = new \stdClass();
        $object->userId = $userId;

        return $object;
    }
}
