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

namespace spec\Sylius\Resource\Context\Option;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Context\Option\RequestOption;
use Symfony\Component\HttpFoundation\Request;

final class RequestOptionSpec extends ObjectBehavior
{
    function let(Request $request): void
    {
        $this->beConstructedWith($request);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RequestOption::class);
    }

    function it_contains_request(Request $request): void
    {
        $this->request()->shouldReturn($request);
    }
}
