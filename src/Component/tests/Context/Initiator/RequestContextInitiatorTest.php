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

namespace Sylius\Resource\Tests\Context\Initiator;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiator;
use Sylius\Resource\Context\Option\RequestOption;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class RequestContextInitiatorTest extends TestCase
{
    private RequestContextInitiator $requestContextInitiator;

    protected function setUp(): void
    {
        $this->requestContextInitiator = new RequestContextInitiator();
    }

    /** @test */
    public function it_initializes_context(): void
    {
        $request = $this->createMock(Request::class);

        $request->attributes = new ParameterBag(['_sylius' => ['resource_class' => 'App\Resource']]);

        $context = $this->requestContextInitiator->initializeContext($request);

        $this->assertInstanceOf(Context::class, $context);
        $this->assertEquals($request, $context->get(RequestOption::class)?->request());
    }
}
