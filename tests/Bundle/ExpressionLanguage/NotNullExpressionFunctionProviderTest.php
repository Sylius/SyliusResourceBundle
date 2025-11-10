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
use Sylius\Bundle\ResourceBundle\ExpressionLanguage\NotNullExpressionFunctionProvider;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class NotNullExpressionFunctionProviderTest extends TestCase
{
    private NotNullExpressionFunctionProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new NotNullExpressionFunctionProvider();
    }

    public function testItProvidesExpressionFunctions(): void
    {
        $functions = $this->provider->getFunctions();

        $this->assertIsArray($functions);
        $this->assertCount(1, $functions);
        $this->assertInstanceOf(ExpressionFunction::class, $functions[0]);
    }

    public function testItProvidesNotFoundOnNullFunction(): void
    {
        $functions = $this->provider->getFunctions();

        $this->assertSame('notFoundOnNull', $functions[0]->getName());
    }

    public function testItReturnsValueWhenNotNull(): void
    {
        $functions = $this->provider->getFunctions();
        $evaluator = $functions[0]->getEvaluator();

        $result = $evaluator([], 'some value');

        $this->assertSame('some value', $result);
    }

    public function testItThrowsNotFoundExceptionWhenNull(): void
    {
        $functions = $this->provider->getFunctions();
        $evaluator = $functions[0]->getEvaluator();

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Requested page is invalid.');

        $evaluator([], null);
    }

    public function testItCompilesExpressionCorrectly(): void
    {
        $functions = $this->provider->getFunctions();
        $compiler = $functions[0]->getCompiler();

        $compiled = $compiler('$result');

        $this->assertStringContainsString('null !== $result', $compiled);
        $this->assertStringContainsString('$result', $compiled);
        $this->assertStringContainsString('throw new NotFoundHttpException', $compiled);
    }
}
