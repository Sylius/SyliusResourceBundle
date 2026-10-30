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

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Grid\Parser\GridExpressionParser;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParser;
use Sylius\Resource\Symfony\ExpressionLanguage\Provider\ThrowNotFoundOnNullExpressionFunctionProvider;
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollection;
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class GridExpressionParserTest extends TestCase
{
    private GridExpressionParser $parser;

    protected function setUp(): void
    {
        $appVariables = new class() implements VariablesInterface {
            public function getVariables(): array
            {
                return ['locale' => 'en_US'];
            }
        };

        $this->parser = new GridExpressionParser(new ArgumentParser(
            new ExpressionLanguage(),
            new VariablesCollection([$appVariables]),
            [new ThrowNotFoundOnNullExpressionFunctionProvider()],
        ));
    }

    public function testItEvaluatesTheExpressionsWithTheGridVariables(): void
    {
        $this->assertSame(
            [
                'repository' => [
                    'method' => 'createListQueryBuilder',
                    'arguments' => ['en_US', 42, '$legacy', 'expr:legacy', 'static'],
                ],
                'link' => ['parameters' => ['id' => 7, 'productId' => 42]],
            ],
            $this->parser->parseExpressions(
                [
                    'repository' => [
                        'method' => 'createListQueryBuilder',
                        'arguments' => ['@=locale', "@=variables['productId']", '$legacy', 'expr:legacy', 'static'],
                    ],
                    'link' => ['parameters' => ['id' => '@=resource.id', 'productId' => "@=variables['productId']"]],
                ],
                ['productId' => 42],
                ['resource' => (object) ['id' => 7]],
            ),
        );
    }

    public function testItUsesTheExpressionFunctions(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->parser->parseExpressions(["@=throw_not_found_on_null(variables['missing'] ?? null)"], []);
    }
}
