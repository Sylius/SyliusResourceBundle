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

namespace Sylius\Component\Resource\Tests\Symfony\ExpressionLanguage;

use Sylius\Component\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ArgumentParserTest extends KernelTestCase
{
    public function testResourceFactoryArgumentParser(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var ArgumentParserInterface $argumentParser */
        $argumentParser = $container->get('sylius.expression_language.argument_parser.factory');

        $this->assertInstanceOf(ArgumentParserInterface::class, $argumentParser);
        $this->assertTrue($argumentParser->parseExpression('token.getUser() === null'));
        $this->assertTrue($argumentParser->parseExpression('user === null'));
    }

    public function testRepositoryArgumentParser(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var ArgumentParserInterface $argumentParser */
        $argumentParser = $container->get('sylius.expression_language.argument_parser.repository');

        $this->assertInstanceOf(ArgumentParserInterface::class, $argumentParser);
        $this->assertTrue($argumentParser->parseExpression('token.getUser() === null'));
        $this->assertTrue($argumentParser->parseExpression('user === null'));
    }

    public function testRoutingArgumentParser(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var ArgumentParserInterface $argumentParser */
        $argumentParser = $container->get('sylius.expression_language.argument_parser.routing');

        $this->assertInstanceOf(ArgumentParserInterface::class, $argumentParser);
    }
}
