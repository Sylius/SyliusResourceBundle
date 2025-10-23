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
use PHPUnit\Framework\MockObject\MockObject;
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

    function testImplementsParametersParserInterface(): void
    {
        $this->assertInstanceOf(ParametersParserInterface::class, $this->parametersParser);
    }

    function testParsesStringParameters(): void
    {
        $request = new Request();
        $request->request->set('string', 'Lorem ipsum');

        $this->assertSame(['nested' => ['string' => 'Lorem ipsum']], $this->parametersParser
            ->parseRequestValues(['nested' => ['string' => '$string']], $request))
        ;
    }

    function testParsesBooleanParameters(): void
    {
        $request = new Request();
        $request->request->set('boolean', true);

        $this->assertSame(['nested' => ['boolean' => true]], $this->parametersParser
            ->parseRequestValues(['nested' => ['boolean' => '$boolean']], $request))
        ;
    }

    function testParsesArrayParameters(): void
    {
        $request = new Request();
        $request->request->set('array', ['foo' => 'bar']);

        $this->assertSame(['nested' => ['array' => ['foo' => 'bar']]], $this->parametersParser
            ->parseRequestValues(['nested' => ['array' => '$array']], $request))
        ;
    }

    function testParsesStringParameterAndCastsItIntoInt(): void
    {
        $request = new Request();
        $request->request->set('int', '5');

        $this->assertSame(['nested' => ['int' => 5]], $this->parametersParser
            ->parseRequestValues(['nested' => ['int' => '!!int $int']], $request))
        ;
    }

    function testParsesStringParameterAndCastsItIntoFloat(): void
    {
        $request = new Request();
        $request->request->set('float', '5.4');

        $this->assertSame(['nested' => ['float' => 5.4]], $this->parametersParser
            ->parseRequestValues(['nested' => ['float' => '!!float $float']], $request))
        ;
    }

    function testThrowsExceptionIfStringParameterIsGoingToBeCastedIntoInvalidType(): void
    {
        $request = new Request();
        $request->request->set('int', 5);
        $this->expectException(\InvalidArgumentException::class);
        $this->parametersParser->parseRequestValues(['nested' => ['int' => '!!invalid $int']], $request);
    }

    function testThrowsExceptionIfInvalidTypecastIsProvided(): void
    {
        $request = new Request();
        $request->request->set('int', 5);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectException(\InvalidArgumentException::class);
        $this->parametersParser->parseRequestValues(['nested' => ['int' => '!!int!! $int']], $request);
        $this->expectException(\InvalidArgumentException::class);
        $this->parametersParser->parseRequestValues(['nested' => ['int' => '!!int!! $int']], $request);
    }

    function testParsesStringParameterAndCastsItIntoBool(): void
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

    function testParsesAnExpressionAndCastsItIntoAGivenType(): void
    {
        $request = new Request();

        $this->assertSame(['nested' => ['cast' => 5]], $this->parametersParser
            ->parseRequestValues(['nested' => ['cast' => '!!int expr:"5"']], $request))
        ;
    }

    function testParsesAnExpressionWithSpacesAndCastsItIntoAGivenType(): void
    {
        $request = new Request();

        $this->assertSame(['nested' => ['cast' => 10]], $this->parametersParser
            ->parseRequestValues(['nested' => ['cast' => '!!int expr:"5" + "5"']], $request))
        ;
    }

    function testParsesExpressions(): void
    {
        $request = new Request();

        $this->assertSame(['nested' => ['boolean' => true]], $this->parametersParser
            ->parseRequestValues(['nested' => ['boolean' => 'expr:"foo" in ["foo", "bar"]']], $request))
        ;
    }

    function testParsesExpressionsWithStringParameters(): void
    {
        $request = new Request();
        $request->request->set('string', 'lorem ipsum');

        $this->assertSame(['expression' => true], $this->parametersParser
            ->parseRequestValues(['expression' => 'expr:$string === "lorem ipsum"'], $request))
        ;
    }

    function testParsesExpressionsWithScalarParameters(): void
    {
        $request = new Request();
        $request->request->set('number', 6);

        $this->assertSame(['expression' => true], $this->parametersParser
            ->parseRequestValues(['expression' => 'expr:$number === 6'], $request))
        ;
    }

    function testThrowsAnExceptionIfArrayParameterIsInjectedIntoExpression(): void
    {
        $request = new Request();
        $request->request->set('array', ['foo', 'bar']);
        $this->expectException(\InvalidArgumentException::class);
        $this->parametersParser->parseRequestValues(['expression' => 'expr:"foo" in $array'], $request);
    }

    function testThrowsAnExceptionIfObjectParameterIsInjectedIntoExpression(): void
    {
        /** @var \Stringable|MockObject $objectMock */
        $objectMock = $this->createMock(\Stringable::class);
        $request = new Request();
        $request->request->set('object', $objectMock);
        $this->expectException(\InvalidArgumentException::class);
        $this->parametersParser->parseRequestValues(['expression' => 'expr:$object.callMethod()'], $request);
    }
}
