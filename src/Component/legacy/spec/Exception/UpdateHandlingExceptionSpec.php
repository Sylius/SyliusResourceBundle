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

namespace spec\Sylius\Component\Resource\Exception;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Exception\UpdateHandlingException as NewUpdateHandlingException;

final class UpdateHandlingExceptionSpec extends ObjectBehavior
{
    function it_should_be_an_alias_of_unsupported_method_exception(): void
    {
        $this->shouldBeAnInstanceOf(NewUpdateHandlingException::class);
    }
}
