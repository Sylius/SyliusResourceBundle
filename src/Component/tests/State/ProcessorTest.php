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
use Sylius\Component\Resource\Tests\Dummy\ProcessorWithCallable;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\State\Processor;
use Sylius\Resource\State\ProcessorInterface;

final class ProcessorTest extends TestCase
{
    use ProphecyTrait;

    private Processor $processor;

    private ContainerInterface|ObjectProphecy $locator;

    protected function setUp(): void
    {
        $this->locator = $this->prophesize(ContainerInterface::class);
        $this->processor = new Processor($this->locator->reveal());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(Processor::class, $this->processor);
    }

    public function testItCallsProcessMethodFromOperationProcessorAsString(): void
    {
        $operation = new Create(processor: '\App\Processor');
        $context = new Context();
        $processor = $this->prophesize(ProcessorInterface::class);

        $this->locator->has('\App\Processor')->willReturn(true);
        $this->locator->get('\App\Processor')->willReturn($processor->reveal());

        $processor->process([], $operation, $context)->shouldBeCalled()->willReturn(null);

        $this->processor->process([], $operation, $context);
    }

    public function testItCallsProcessMethodFromOperationProcessorAsCallable(): void
    {
        $operation = new Create(processor: [ProcessorWithCallable::class, 'process']);
        $context = new Context();

        $result = $this->processor->process([], $operation, $context);

        $this->assertInstanceOf(\stdClass::class, $result);
    }

    public function testItReturnsNullIfOperationHasNoProcessor(): void
    {
        $operation = new Create();
        $context = new Context();

        $result = $this->processor->process([], $operation, $context);

        $this->assertNull($result);
    }

    public function testItThrowsExceptionWhenConfiguredProcessorIsNotAProcessorInstance(): void
    {
        $operation = new Create(processor: '\stdClass');
        $context = new Context();

        $this->locator->has('\stdClass')->willReturn(true);
        $this->locator->get('\stdClass')->willReturn(new \stdClass());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected an instance of Sylius\Resource\State\ProcessorInterface. Got: stdClass');

        $this->processor->process([], $operation, $context);
    }
}
