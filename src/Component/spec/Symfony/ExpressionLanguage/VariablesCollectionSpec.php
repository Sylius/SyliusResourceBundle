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

namespace spec\Sylius\Resource\Symfony\ExpressionLanguage;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollection;
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesInterface;

final class VariablesCollectionSpec extends ObjectBehavior
{
    function let(
        VariablesInterface $firstVariables,
        VariablesInterface $secondVariables,
    ): void {
        $this->beConstructedWith([$firstVariables->getWrappedObject(), $secondVariables->getWrappedObject()]);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(VariablesCollection::class);
    }

    function it_merges_variables(
        VariablesInterface $firstVariables,
        VariablesInterface $secondVariables,
    ): void {
        $firstVariables->getVariables()->willReturn(['foo' => 'bar', 'user' => '123']);
        $secondVariables->getVariables()->willReturn(['foo' => 'fighters', 'value' => 'xyz']);

        $this->getVariables()->shouldReturn([
            'foo' => 'fighters',
            'user' => '123',
            'value' => 'xyz',
        ]);
    }
}
