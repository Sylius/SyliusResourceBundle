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

namespace spec\Sylius\Component\Resource\Reflection;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Reflection\CallableReflection;
use Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables;

class CallableReflectionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(CallableReflection::class);
    }

    function it_reflects_an_array_callable(): void
    {
        $this::from([RepositoryWithCallables::class, 'find'])->shouldHaveType(\ReflectionFunctionAbstract::class);
    }

    function it_reflects_a_closure_callable(): void
    {
        $this::from(fn (): array => [])->shouldHaveType(\ReflectionFunctionAbstract::class);
    }

    function it_reflects_a_string_callable(): void
    {
        $this::from('Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables::find')->shouldHaveType(\ReflectionFunctionAbstract::class);
    }

    function it_reflects_an_invokable_callable(): void
    {
        $this::from(new RepositoryWithCallables())->shouldHaveType(\ReflectionFunctionAbstract::class);
    }
}
