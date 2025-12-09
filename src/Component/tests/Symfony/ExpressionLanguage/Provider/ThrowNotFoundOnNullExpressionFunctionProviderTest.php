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

    private ThrowNotFoundOnNullExpressionFunctionProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new ThrowNotFoundOnNullExpressionFunctionProvider();
        $this->expressionLanguage = new ExpressionLanguage();
        $this->expressionLanguage->registerProvider($this->provider);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ThrowNotFoundOnNullExpressionFunctionProvider::class, $this->provider);
    }

    public function testItProvidesFunctions(): void
    {
        $functions = $this->provider->getFunctions();

        $this->assertIsArray($functions);
        $this->assertCount(1, $functions);
        $this->assertContainsOnlyInstancesOf(\Symfony\Component\ExpressionLanguage\ExpressionFunction::class, $functions);
    }

    public function testItReturnsTheValueWhenNotNull(): void
    {
        $result = $this->expressionLanguage->evaluate('throw_not_found_on_null(value)', [
            'value' => 'foo',
        ]);

        $this->assertSame('foo', $result);
    }

    public function testItReturnsNumericValue(): void
    {
        $result = $this->expressionLanguage->evaluate('throw_not_found_on_null(value)', [
            'value' => 123,
        ]);

        $this->assertSame(123, $result);
    }

    public function testItReturnsArrayValue(): void
    {
        $result = $this->expressionLanguage->evaluate('throw_not_found_on_null(value)', [
            'value' => ['key' => 'value'],
        ]);

        $this->assertSame(['key' => 'value'], $result);
    }

    public function testItReturnsObjectValue(): void
    {
        $object = new \stdClass();
        $object->property = 'value';

        $result = $this->expressionLanguage->evaluate('throw_not_found_on_null(value)', [
            'value' => $object,
        ]);

        $this->assertSame($object, $result);
        $this->assertSame('value', $result->property);
    }

    public function testItReturnsFalseValue(): void
    {
        $result = $this->expressionLanguage->evaluate('throw_not_found_on_null(value)', [
            'value' => false,
        ]);

        $this->assertFalse($result);
    }

    public function testItReturnsZeroValue(): void
    {
        $result = $this->expressionLanguage->evaluate('throw_not_found_on_null(value)', [
            'value' => 0,
        ]);

        $this->assertSame(0, $result);
    }

    public function testItReturnsEmptyStringValue(): void
    {
        $result = $this->expressionLanguage->evaluate('throw_not_found_on_null(value)', [
            'value' => '',
        ]);

        $this->assertSame('', $result);
    }

    public function testItThrowsNotFoundExceptionWhenNullWithEmptyMessage(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('');

        $this->expressionLanguage->evaluate('throw_not_found_on_null(value)', [
            'value' => null,
        ]);
    }

    public function testItThrowsNotFoundExceptionWhenNullWithCustomMessage(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Custom message.');

        $this->expressionLanguage->evaluate('throw_not_found_on_null(value, "Custom message.")', [
            'value' => null,
        ]);
    }

    public function testItThrowsNotFoundExceptionWithDetailedMessage(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Resource with ID 123 was not found.');

        $this->expressionLanguage->evaluate('throw_not_found_on_null(value, "Resource with ID 123 was not found.")', [
            'value' => null,
        ]);
    }

    public function testItCompilesExpressionWithoutMessage(): void
    {
        $compiled = $this->expressionLanguage->compile('throw_not_found_on_null(value)', ['value']);

        $this->assertIsString($compiled);
        $this->assertStringContainsString('null !== $value', $compiled);
        $this->assertStringContainsString('NotFoundHttpException()', $compiled);
        $this->assertSame(
            '(null !== $value) ? $value : throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException()',
            $compiled,
        );
    }

    public function testItCompilesExpressionWithMessage(): void
    {
        $compiled = $this->expressionLanguage->compile('throw_not_found_on_null(value, "Not found")', ['value']);

        $this->assertIsString($compiled);
        $this->assertStringContainsString('null !== $value', $compiled);
        $this->assertStringContainsString('NotFoundHttpException("Not found")', $compiled);
        $this->assertSame(
            '(null !== $value) ? $value : throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("Not found")',
            $compiled,
        );
    }

    public function testItCompilesExpressionWithComplexMessage(): void
    {
        $compiled = $this->expressionLanguage->compile(
            'throw_not_found_on_null(value, "Resource not found with the given parameters")',
            ['value'],
        );

        $this->assertIsString($compiled);
        $this->assertStringContainsString('NotFoundHttpException("Resource not found with the given parameters")', $compiled);
    }
}
