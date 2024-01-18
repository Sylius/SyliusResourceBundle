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

namespace spec\Sylius\Resource\Doctrine\Persistence\Exception;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Doctrine\Persistence\Exception\ExceptionInterface;

final class ResourceExistsExceptionSpec extends ObjectBehavior
{
    function it_extends_exception(): void
    {
        $this->shouldHaveType(\Exception::class);
    }

    function it_extends_runtime_exception(): void
    {
        $this->shouldHaveType(\RuntimeException::class);
    }

    function it_implements_exception_interface(): void
    {
        $this->shouldImplement(ExceptionInterface::class);
    }

    function it_has_a_message(): void
    {
        $this->getMessage()->shouldReturn('Given resource already exists in the repository.');
    }
}
