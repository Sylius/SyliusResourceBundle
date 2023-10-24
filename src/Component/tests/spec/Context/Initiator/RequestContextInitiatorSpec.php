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

namespace spec\Sylius\Resource\Context\Initiator;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiator;
use Sylius\Resource\Context\Option\RequestOption;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class RequestContextInitiatorSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(RequestContextInitiator::class);
    }

    function it_initializes_context(Request $request): void
    {
        $request->attributes = new ParameterBag(['_sylius' => ['resource_class' => 'App\Resource']]);

        $context = $this->initializeContext($request);
        $context->shouldHaveType(Context::class);

        $context->get(RequestOption::class)->request()->shouldReturn($request);
    }
}
