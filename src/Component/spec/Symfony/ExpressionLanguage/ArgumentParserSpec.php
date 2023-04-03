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

namespace spec\Sylius\Component\Resource\Symfony\ExpressionLanguage;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\ExpressionLanguage\ExpressionLanguage;
use Sylius\Component\Resource\Symfony\ExpressionLanguage\ArgumentParser;
use Sylius\Component\Resource\Symfony\ExpressionLanguage\VariablesCollectionInterface;

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
}
