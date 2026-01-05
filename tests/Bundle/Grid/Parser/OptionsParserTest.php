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

namespace Sylius\Bundle\ResourceBundle\Tests\Grid\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParser;
use Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParserInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class OptionsParserTest extends TestCase
{
    /** @var ContainerInterface&MockObject */
    private ContainerInterface $container;

    /** @var ExpressionLanguage&MockObject */
    private ExpressionLanguage $expression;

    /** @var PropertyAccessorInterface&MockObject */
    private PropertyAccessorInterface $propertyAccessor;

    private OptionsParser $parser;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->expression = $this->createMock(ExpressionLanguage::class);
        $this->propertyAccessor = $this->createMock(PropertyAccessorInterface::class);
        $this->parser = new OptionsParser($this->container, $this->expression, $this->propertyAccessor);
    }

    public function testItImplementsOptionsParserInterface(): void
    {
        $this->assertInstanceOf(OptionsParserInterface::class, $this->parser);
    }

    public function testItParsesOptionsWithRequestVariable(): void
    {
        $result = $this->parser->parseOptions(['id' => '$id'], new Request(attributes: ['id' => 7]));

        $this->assertSame(['id' => 7], $result);
    }

    public function testItReturnsNonStringParametersWithoutParsing(): void
    {
        $data = [
            'integer' => 10,
            'float' => 19.99,
            'true' => true,
            'false' => false,
            'null' => null,
        ];

        $result = $this->parser->parseOptions($data, new Request());

        $this->assertSame($data, $result);
    }

    public function testItParsesOptionsWithMixedTypes(): void
    {
        $result = $this->parser->parseOptions([
            'limit' => 25,
            'enabled' => true,
            'offset' => 0,
            'price' => 99.99,
            'optional' => null,
            'status' => '$status',
        ], new Request(query: ['status' => 'active']));

        $this->assertSame([
            'limit' => 25,
            'enabled' => true,
            'offset' => 0,
            'price' => 99.99,
            'optional' => null,
            'status' => 'active',
        ], $result);
    }

    public function testItParsesOptionsWithExpression(): void
    {
        $this->configureExpressionEvaluation('service("demo_service")', 'demo_object');

        $result = $this->parser->parseOptions(
            [
                'factory' => [
                    'method' => 'createByParameter',
                    'arguments' => [
                        'expr:service("demo_service")',
                    ],
                ],
            ],
            new Request(),
        );

        $this->assertSame(
            [
                'factory' => [
                    'method' => 'createByParameter',
                    'arguments' => [
                        'demo_object',
                    ],
                ],
            ],
            $result,
        );
    }

    public function testItParsesOptionsWithParameterFromResource(): void
    {
        $data = $this->createMock(ResourceInterface::class);

        $this->propertyAccessor->expects($this->once())
            ->method('getValue')
            ->with($data, 'id')
            ->willReturn(21);

        $result = $this->parser->parseOptions(['id' => 'resource.id'], new Request(), $data);

        $this->assertSame(['id' => 21], $result);
    }

    public function testItParsesOptionsWithArrayResourceAccess(): void
    {
        $data = $this->createMock(ResourceInterface::class);
        $arrayData = [0 => $data, 'name' => 'An awesome name'];

        $this->propertyAccessor->expects($this->exactly(2))
            ->method('getValue')
            ->willReturnCallback(fn ($subject, $path) => match ($path) {
                'name' => 'An awesome name',
                '[0].id' => 21,
                default => null,
            });

        $result = $this->parser->parseOptions(['name' => 'resource.name'], new Request(), $arrayData);
        $this->assertSame(['name' => 'An awesome name'], $result);

        $result = $this->parser->parseOptions(['id' => 'resource[0].id'], new Request(), $arrayData);
        $this->assertSame(['id' => 21], $result);
    }

    /**
     * @param array<string, mixed>|null $requestVariables
     * @param array<string, string> $options
     * @param array<string, mixed> $expectedResult
     */
    #[DataProvider('expressionVariableTypesProvider')]
    public function testItParsesExpressionWithVariableFromRequest(
        ?array $requestVariables,
        string $expectedExpression,
        array $options,
        array $expectedResult,
    ): void {
        $this->configureExpressionEvaluation($expectedExpression, reset($expectedResult));

        $result = $this->parser->parseOptions($options, new Request(attributes: $requestVariables ?? []));

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return iterable<string, array{array<string, mixed>|null, string, array<string, string>, array<string, mixed>}>
     */
    public static function expressionVariableTypesProvider(): iterable
    {
        yield 'string variable' => [
            ['customerId' => 'user123'],
            'service("customer_repository").find("user123")',
            ['customer' => 'expr:service("customer_repository").find($customerId)'],
            ['customer' => 'customer_object'],
        ];

        yield 'integer variable' => [
            ['productId' => 42],
            'service("product_repository").find(42)',
            ['product' => 'expr:service("product_repository").find($productId)'],
            ['product' => 'product_object'],
        ];

        yield 'null variable' => [
            ['optionalParam' => null],
            'service("some_service").process()',
            ['data' => 'expr:service("some_service").process($optionalParam)'],
            ['data' => 'result'],
        ];

        yield 'string with special characters' => [
            ['searchTerm' => 'user"test\\value'],
            'service("search").find("user\"test\\\\value")',
            ['results' => 'expr:service("search").find($searchTerm)'],
            ['results' => 'search_results'],
        ];

        yield 'multiple variables' => [
            ['userId' => 10, 'limit' => 5],
            'service("user_repository").findLatest(10, 5)',
            ['users' => 'expr:service("user_repository").findLatest($userId, $limit)'],
            ['users' => 'user_list'],
        ];

        yield 'boolean variable' => [
            ['enabled' => true],
            'service("filter").apply(1)',
            ['data' => 'expr:service("filter").apply($enabled)'],
            ['data' => 'filtered_results'],
        ];
    }

    private function configureExpressionEvaluation(string $expression, mixed $result): void
    {
        $this->expression->expects($this->once())
            ->method('evaluate')
            ->with($expression, ['container' => $this->container])
            ->willReturn($result);
    }
}
