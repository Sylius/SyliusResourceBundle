<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Tests\Symfony\ExpressionLanguage;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ArgumentParserTest extends KernelTestCase
{
    public function testResourceFactoryArgumentParser(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $resourceFactoryArgumentParser = $container->get('sylius.expression_language.argument_parser.factory');

        $this->assertTrue($resourceFactoryArgumentParser->parseExpression('token.getUser() === null'));
        $this->assertTrue($resourceFactoryArgumentParser->parseExpression('user === null'));
    }

    public function testRepositoryArgumentParser(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $resourceFactoryArgumentParser = $container->get('sylius.expression_language.argument_parser.repository');

        $this->assertTrue($resourceFactoryArgumentParser->parseExpression('token.getUser() === null'));
        $this->assertTrue($resourceFactoryArgumentParser->parseExpression('user === null'));
    }
}
