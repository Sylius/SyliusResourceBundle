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

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParser;
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollectionInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ArgumentParserTest extends TestCase
{
    private ArgumentParser $argumentParser;

    private VariablesCollectionInterface $variablesCollection;

    protected function setUp(): void
    {
        $this->variablesCollection = $this->createMock(VariablesCollectionInterface::class);
        $this->argumentParser = new ArgumentParser(new ExpressionLanguage(), $this->variablesCollection);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ArgumentParser::class, $this->argumentParser);
    }

    public function testItParsesExpressions(): void
    {
        $this->variablesCollection->method('getVariables')->willReturn(['foo' => 'fighters']);

        $result = $this->argumentParser->parseExpression('foo');

        $this->assertSame('fighters', $result);
    }

    public function testItMergesVariables(): void
    {
        $this->variablesCollection->method('getVariables')->willReturn(['foo' => 'fighters']);

        $result = $this->argumentParser->parseExpression('foo', ['foo' => 'bar']);

        $this->assertSame('bar', $result);
    }
}
