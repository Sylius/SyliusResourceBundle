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

namespace Sylius\Bundle\ResourceBundle\Tests\ExpressionLanguage;

use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Sylius\Bundle\ResourceBundle\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

final class ExpressionLanguageTest extends TestCase
{
    public function testItCanBeInstantiatedWithoutCache(): void
    {
        $expressionLanguage = new ExpressionLanguage();

        $this->assertInstanceOf(ExpressionLanguage::class, $expressionLanguage);
    }

    public function testItCanBeInstantiatedWithCacheItemPool(): void
    {
        $cache = $this->createMock(CacheItemPoolInterface::class);

        $expressionLanguage = new ExpressionLanguage($cache);

        $this->assertInstanceOf(ExpressionLanguage::class, $expressionLanguage);
    }

    public function testItCanBeInstantiatedWithProviders(): void
    {
        $provider = $this->createMock(ExpressionFunctionProviderInterface::class);
        $provider->method('getFunctions')->willReturn([]);

        $expressionLanguage = new ExpressionLanguage(null, [$provider]);

        $this->assertInstanceOf(ExpressionLanguage::class, $expressionLanguage);
    }

    public function testItThrowsExceptionForInvalidCacheType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cache argument has to implement Psr\Cache\CacheItemPoolInterface');

        new ExpressionLanguage('invalid');
    }

    public function testItRegistersNotNullExpressionFunctionProvider(): void
    {
        $expressionLanguage = new ExpressionLanguage();

        $result = $expressionLanguage->evaluate('notFoundOnNull(value)', ['value' => 'test']);

        $this->assertSame('test', $result);
    }

    public function testItCompilesExpressionsWithNotFoundOnNullFunction(): void
    {
        $expressionLanguage = new ExpressionLanguage();

        $compiled = $expressionLanguage->compile('notFoundOnNull(value)', ['value']);

        $this->assertStringContainsString('null !== ', $compiled);
    }
}
