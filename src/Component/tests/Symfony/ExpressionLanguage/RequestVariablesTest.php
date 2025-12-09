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

namespace Sylius\Resource\Tests\Symfony\ExpressionLanguage;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Symfony\ExpressionLanguage\RequestVariables;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestVariablesTest extends TestCase
{
    private RequestStack $requestStack;

    private RequestVariables $requestVariables;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->requestVariables = new RequestVariables($this->requestStack);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(RequestVariables::class, $this->requestVariables);
    }

    public function testItReturnsRequestVars(): void
    {
        $request = $this->createMock(Request::class);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $result = $this->requestVariables->getVariables();

        $this->assertSame(['request' => $request], $result);
    }
}
