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
use Sylius\Bundle\ResourceBundle\Grid\ExpressionLanguage\GridExpressionVariables;
use Sylius\Bundle\ResourceBundle\Grid\Parser\LegacyGridExpressionParser;
use Sylius\Component\Grid\ExpressionLanguage\ExpressionEvaluator;
use Sylius\Component\Grid\ExpressionLanguage\ExpressionLanguageFactory;
use Sylius\Component\Grid\ExpressionLanguage\VariablesCollectionAggregate;
use Sylius\Resource\Symfony\ExpressionLanguage\Provider\ThrowNotFoundOnNullExpressionFunctionProvider;
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollection;
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class LegacyGridExpressionParserTest extends TestCase
{
    private LegacyGridExpressionParser $parser;

    protected function setUp(): void
    {
        $appVariables = new class() implements VariablesInterface {
            public function getVariables(): array
            {
                return ['locale' => 'en_US'];
            }
        };

        // The resource variables and functions, as registered in the grid expression evaluator
        $this->parser = new LegacyGridExpressionParser(new ExpressionEvaluator(
            (new ExpressionLanguageFactory([new ThrowNotFoundOnNullExpressionFunctionProvider()]))(),
            new VariablesCollectionAggregate([new GridExpressionVariables(new VariablesCollection([$appVariables]))]),
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
