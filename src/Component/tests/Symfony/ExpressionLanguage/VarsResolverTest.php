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

namespace Sylius\Resource\Tests\Symfony\ExpressionLanguage;

use Sylius\Resource\Symfony\ExpressionLanguage\VarsResolverInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class VarsResolverTest extends KernelTestCase
{
    private VarsResolverInterface $varsResolver;

    protected function setUp(): void
    {
        $container = static::getContainer();

        /** @var VarsResolverInterface $varsResolver */
        $this->varsResolver = $container->get('sylius.expression_language.vars_resolver.metadata');
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(VarsResolverInterface::class, $this->varsResolver);
    }

    public function testItResolvesSimpleExpression(): void
    {
        $result = $this->varsResolver->resolve(['has_user' => '@=token.getUser() === null']);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('has_user', $result);
        $this->assertTrue($result['has_user']);
    }

    public function testItResolvesNestedExpression(): void
    {
        $result = $this->varsResolver->resolve(['parameters' => ['has_user' => '@=token.getUser() === null']]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('parameters', $result);
        $this->assertIsArray($result['parameters']);
        $this->assertArrayHasKey('has_user', $result['parameters']);
        $this->assertTrue($result['parameters']['has_user']);
    }

    public function testItDoesNotProcessStringValuesWithoutExpressionPrefix(): void
    {
        $result = $this->varsResolver->resolve(['name' => 'John']);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('name', $result);
        $this->assertSame('John', $result['name']);
    }

    public function testItDoesNotProcessNumericStrings(): void
    {
        $result = $this->varsResolver->resolve(['count' => '123']);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('count', $result);
        $this->assertSame('123', $result['count']);
    }

    public function testItDoesNotProcessBooleanStrings(): void
    {
        $result = $this->varsResolver->resolve(['flag' => 'true']);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('flag', $result);
        $this->assertSame('true', $result['flag']);
    }

    public function testItDoesNotProcessEmptyStrings(): void
    {
        $result = $this->varsResolver->resolve(['empty' => '']);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('empty', $result);
        $this->assertSame('', $result['empty']);
    }

    public function testItHandlesMixedValuesWithAndWithoutExpressions(): void
    {
        $result = $this->varsResolver->resolve([
            'name' => 'John',
            'has_user' => '@=token.getUser() === null',
        ]);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('has_user', $result);
        $this->assertSame('John', $result['name']);
        $this->assertTrue($result['has_user']);
    }

    public function testItHandlesNestedArraysWithMixedValues(): void
    {
        $result = $this->varsResolver->resolve([
            'parameters' => [
                'name' => 'John',
                'has_user' => '@=token.getUser() === null',
            ],
        ]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('parameters', $result);
        $this->assertIsArray($result['parameters']);
        $this->assertCount(2, $result['parameters']);
        $this->assertSame('John', $result['parameters']['name']);
        $this->assertTrue($result['parameters']['has_user']);
    }

    public function testItReturnsEmptyArrayWhenGivenEmptyArray(): void
    {
        $result = $this->varsResolver->resolve([]);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testItResolvesMultipleExpressions(): void
    {
        $result = $this->varsResolver->resolve([
            'has_user' => '@=token.getUser() === null',
            'is_authenticated' => '@=token.getUser() !== null',
        ]);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('has_user', $result);
        $this->assertArrayHasKey('is_authenticated', $result);
        $this->assertTrue($result['has_user']);
        $this->assertFalse($result['is_authenticated']);
    }

    public function testItHandlesDeeplyNestedArrays(): void
    {
        $result = $this->varsResolver->resolve([
            'level1' => [
                'level2' => [
                    'has_user' => '@=token.getUser() === null',
                    'name' => 'test',
                ],
            ],
        ]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('level1', $result);
        $this->assertIsArray($result['level1']);
        $this->assertArrayHasKey('level2', $result['level1']);
        $this->assertIsArray($result['level1']['level2']);
        $this->assertTrue($result['level1']['level2']['has_user']);
        $this->assertSame('test', $result['level1']['level2']['name']);
    }
}
