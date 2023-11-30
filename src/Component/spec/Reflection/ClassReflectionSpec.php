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
use Sylius\Resource\Reflection\ClassReflection as NewClassReflection;

final class ClassReflectionSpec extends ObjectBehavior
{
    function it_is_an_alias_of_the_class_reflection(): void
    {
        $this->shouldBeAnInstanceOf(NewClassReflection::class);
    }
}
