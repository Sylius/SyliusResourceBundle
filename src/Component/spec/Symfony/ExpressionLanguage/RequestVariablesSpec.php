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
use Sylius\Component\Resource\Symfony\ExpressionLanguage\RequestVariables;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestVariablesSpec extends ObjectBehavior
{
    function let(RequestStack $requestStack): void
    {
        $this->beConstructedWith($requestStack);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RequestVariables::class);
    }

    function it_returns_request_vars(
        RequestStack $requestStack,
        Request $request,
    ): void {
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->getVariables()->shouldReturn([
            'request' => $request,
        ]);
    }
}
