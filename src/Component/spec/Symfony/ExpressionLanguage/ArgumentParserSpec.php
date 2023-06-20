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

namespace spec\Sylius\Component\Resource\Symfony\ExpressionLanguage;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Symfony\ExpressionLanguage\ArgumentParser;
use Sylius\Component\Resource\Symfony\ExpressionLanguage\VariablesCollectionInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ArgumentParserSpec extends ObjectBehavior
{
    function let(VariablesCollectionInterface $variablesCollection): void
    {
        $this->beConstructedWith(new ExpressionLanguage(), $variablesCollection);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ArgumentParser::class);
    }

    function it_parses_expressions(VariablesCollectionInterface $variablesCollection): void
    {
        $variablesCollection->getVariables()->willReturn(['foo' => 'fighters']);

        $this->parseExpression('foo')->shouldReturn('fighters');
    }

    function it_merges_variables(VariablesCollectionInterface $variablesCollection): void
    {
        $variablesCollection->getVariables()->willReturn(['foo' => 'fighters']);

        $this->parseExpression('foo', ['foo' => 'bar'])->shouldReturn('bar');
    }
}
