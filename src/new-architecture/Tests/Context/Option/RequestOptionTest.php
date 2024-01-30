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

namespace Sylius\Resource\Tests\Context\Option;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Option\RequestOption;
use Symfony\Component\HttpFoundation\Request;

final class RequestOptionTest extends TestCase
{
    use ProphecyTrait;

    private Request|ObjectProphecy $request;

    private RequestOption $requestOption;

    protected function setUp(): void
    {
        $this->request = $this->prophesize(Request::class);

        $this->requestOption = new RequestOption($this->request->reveal());
    }

    /** @test */
    public function it_contains_request(): void
    {
        $this->assertEquals($this->request->reveal(), $this->requestOption->request());
    }
}
