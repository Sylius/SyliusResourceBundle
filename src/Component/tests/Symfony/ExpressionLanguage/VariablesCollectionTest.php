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
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollection;
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesInterface;

final class VariablesCollectionTest extends TestCase
{
    public function testItIsInitializable(): void
    {
        $firstVariables = $this->createMock(VariablesInterface::class);
        $secondVariables = $this->createMock(VariablesInterface::class);

        $variablesCollection = new VariablesCollection([$firstVariables, $secondVariables]);

        $this->assertInstanceOf(VariablesCollection::class, $variablesCollection);
    }

    public function testItMergesVariables(): void
    {
        $firstVariables = $this->createMock(VariablesInterface::class);
        $secondVariables = $this->createMock(VariablesInterface::class);

        $firstVariables->method('getVariables')->willReturn(['foo' => 'bar', 'user' => '123']);
        $secondVariables->method('getVariables')->willReturn(['foo' => 'fighters', 'value' => 'xyz']);

        $variablesCollection = new VariablesCollection([$firstVariables, $secondVariables]);

        $result = $variablesCollection->getVariables();

        $this->assertSame([
            'foo' => 'fighters',
            'user' => '123',
            'value' => 'xyz',
        ], $result);
    }
}
