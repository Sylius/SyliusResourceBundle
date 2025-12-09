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

namespace Sylius\Bundle\ResourceBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\ParametersParser;
use Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;

final class ParametersParserTest extends TestCase
{
    private ParametersParser $parametersParser;

    protected function setUp(): void
    {
        $this->parametersParser = new ParametersParser(new Container(), new ExpressionLanguage());
    }

    public function testImplementsParametersParserInterface(): void
    {
        $this->assertInstanceOf(ParametersParserInterface::class, $this->parametersParser);
    }

    public function testParsesStringParameters(): void
    {
        $request = new Request();
        $request->request->set('string', 'Lorem ipsum');

        $this->assertSame(['nested' => ['string' => 'Lorem ipsum']], $this->parametersParser
            ->parseRequestValues(['nested' => ['string' => '$string']], $request))
        ;
    }

    public function testParsesBooleanParameters(): void
    {
        $request = new Request();
        $request->request->set('boolean', true);

        $this->assertSame(['nested' => ['boolean' => true]], $this->parametersParser
            ->parseRequestValues(['nested' => ['boolean' => '$boolean']], $request))
        ;
    }

    public function testParsesArrayParameters(): void
    {
        $request = new Request();
        $request->request->set('array', ['foo' => 'bar']);

        $this->assertSame(['nested' => ['array' => ['foo' => 'bar']]], $this->parametersParser
            ->parseRequestValues(['nested' => ['array' => '$array']], $request))
        ;
    }

    public function testParsesStringParameterAndCastsItIntoInt(): void
    {
        $request = new Request();
        $request->request->set('int', '5');

        $this->assertSame(['nested' => ['int' => 5]], $this->parametersParser
            ->parseRequestValues(['nested' => ['int' => '!!int $int']], $request))
        ;
    }

    public function testParsesStringParameterAndCastsItIntoFloat(): void
    {
        $request = new Request();
        $request->request->set('float', '5.4');

        $this->assertSame(['nested' => ['float' => 5.4]], $this->parametersParser
            ->parseRequestValues(['nested' => ['float' => '!!float $float']], $request))
        ;
    }

    public function testThrowsExceptionIfStringParameterIsGoingToBeCastedIntoInvalidType(): void
    {
        $request = new Request();
        $request->request->set('int', 5);
        $this->expectException(\InvalidArgumentException::class);
        $this->parametersParser->parseRequestValues(['nested' => ['int' => '!!invalid $int']], $request);
    }

    public function testThrowsExceptionIfInvalidTypecastIsProvided(): void
    {
        $request = new Request();
        $request->request->set('int', 5);

        $this->expectException(\InvalidArgumentException::class);

        $this->parametersParser->parseRequestValues(['nested' => ['int' => '!!int!! $int']], $request);
    }

    public function testParsesStringParameterAndCastsItIntoBool(): void
    {
        $request = new Request();
        $request->request->set('bool0', '0');
        $request->request->set('bool1', '1');

        $this->assertSame(['nested' => ['bool' => false]], $this->parametersParser
            ->parseRequestValues(['nested' => ['bool' => '!!bool $bool0']], $request))
        ;

        $this->assertSame(['nested' => ['bool' => true]], $this->parametersParser
            ->parseRequestValues(['nested' => ['bool' => '!!bool $bool1']], $request))
        ;
    }

    public function testParsesAnExpressionAndCastsItIntoAGivenType(): void
    {
        $request = new Request();

        $this->assertSame(['nested' => ['cast' => 5]], $this->parametersParser
            ->parseRequestValues(['nested' => ['cast' => '!!int expr:"5"']], $request))
        ;
    }

    public function testParsesAnExpressionWithSpacesAndCastsItIntoAGivenType(): void
    {
        $request = new Request();

        $this->assertSame(['nested' => ['cast' => 10]], $this->parametersParser
            ->parseRequestValues(['nested' => ['cast' => '!!int expr:"5" + "5"']], $request))
        ;
    }

    public function testParsesExpressions(): void
    {
        $request = new Request();

        $this->assertSame(['nested' => ['boolean' => true]], $this->parametersParser
            ->parseRequestValues(['nested' => ['boolean' => 'expr:"foo" in ["foo", "bar"]']], $request))
        ;
    }

    public function testParsesExpressionsWithStringParameters(): void
    {
        $request = new Request();
        $request->request->set('string', 'lorem ipsum');

        $this->assertSame(['expression' => true], $this->parametersParser
            ->parseRequestValues(['expression' => 'expr:$string === "lorem ipsum"'], $request))
        ;
    }

    public function testParsesExpressionsWithScalarParameters(): void
    {
        $request = new Request();
        $request->request->set('number', 6);

        $this->assertSame(['expression' => true], $this->parametersParser
            ->parseRequestValues(['expression' => 'expr:$number === 6'], $request))
        ;
    }

    public function testThrowsAnExceptionIfArrayParameterIsInjectedIntoExpression(): void
    {
        $request = new Request();
        $request->request->set('array', ['foo', 'bar']);
        $this->expectException(\InvalidArgumentException::class);
        $this->parametersParser->parseRequestValues(['expression' => 'expr:"foo" in $array'], $request);
    }

    public function testThrowsAnExceptionIfObjectParameterIsInjectedIntoExpression(): void
    {
        /** @var array|bool|float|int|string|null $objectMock */
        $objectMock = $this->createMock(\Stringable::class);
        $request = new Request();
        $request->request->set('object', $objectMock);
        $this->expectException(\InvalidArgumentException::class);
        $this->parametersParser->parseRequestValues(['expression' => 'expr:$object.callMethod()'], $request);
    }
}
