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

namespace Sylius\Bundle\ResourceBundle\Tests\Bundle\Twig\Context;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Twig\Context\LegacyContextFactory;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\MetadataOption;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;

final class LegacyContextFactoryTest extends TestCase
{
    private ContextFactoryInterface $decorated;

    private LegacyContextFactory $factory;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(ContextFactoryInterface::class);
        $this->factory = new LegacyContextFactory($this->decorated);
    }

    public function testItImplementsContextFactoryInterface(): void
    {
        $this->assertInstanceOf(ContextFactoryInterface::class, $this->factory);
    }

    public function testItAddsLegacyTwigVariablesWhenContextContainsOptions(): void
    {
        $data = new \stdClass();
        $operation = $this->createMock(Operation::class);
        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $metadata = $this->createMock(MetadataInterface::class);

        $context = new Context(
            new RequestConfigurationOption($requestConfiguration),
            new MetadataOption($metadata),
        );

        $this->decorated
            ->expects($this->once())
            ->method('create')
            ->with($data, $operation, $context)
            ->willReturn(['resource' => $data]);

        $result = $this->factory->create($data, $operation, $context);

        $this->assertSame([
            'configuration' => $requestConfiguration,
            'metadata' => $metadata,
            'resource' => $data,
        ], $result);
    }

    public function testItDoesNotAddLegacyTwigVariablesWhenContextIsEmpty(): void
    {
        $data = new \stdClass();
        $operation = $this->createMock(Operation::class);
        $context = new Context();

        $this->decorated
            ->expects($this->once())
            ->method('create')
            ->with($data, $operation, $context)
            ->willReturn(['resource' => $data]);

        $result = $this->factory->create($data, $operation, $context);

        $this->assertSame(['resource' => $data], $result);
    }
}
