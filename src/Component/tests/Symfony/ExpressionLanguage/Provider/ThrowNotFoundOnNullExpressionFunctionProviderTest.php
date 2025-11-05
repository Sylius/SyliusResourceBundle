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

namespace Sylius\Resource\Tests\Symfony\ExpressionLanguage\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Symfony\ExpressionLanguage\Provider\ThrowNotFoundOnNullExpressionFunctionProvider;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ThrowNotFoundOnNullExpressionFunctionProviderTest extends TestCase
{
    private ExpressionLanguage $expressionLanguage;

    protected function setUp(): void
    {
        $this->expressionLanguage = new ExpressionLanguage();
        $this->expressionLanguage->registerProvider(new ThrowNotFoundOnNullExpressionFunctionProvider());
    }

    public function test_it_returns_the_value_when_not_null(): void
    {
        $result = $this->expressionLanguage->evaluate('throw_not_found_on_null(value)', [
            'value' => 'foo',
        ]);

        $this->assertSame('foo', $result);
    }

    public function test_it_throws_not_found_exception_when_null_with_empty_message(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('');

        $this->expressionLanguage->evaluate('throw_not_found_on_null(value)', [
            'value' => null,
        ]);
    }

    public function test_it_throws_not_found_exception_when_null_with_custom_message(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Custom message.');

        $this->expressionLanguage->evaluate('throw_not_found_on_null(value, "Custom message.")', [
            'value' => null,
        ]);
    }
}
