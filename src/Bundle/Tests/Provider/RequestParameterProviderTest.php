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

namespace Sylius\Bundle\ResourceBundle\Tests\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Symfony\Request\Provider\RequestParameterProvider;
use Symfony\Component\HttpFoundation\Request;

final class RequestParameterProviderTest extends TestCase
{
    /** @test */
    public function it_returns_request_attribute_parameter_value(): void
    {
        $request = new Request(['foo' => 'bar_query'], ['foo' => 'bar_request'], ['foo' => 'bar_attribute']);

        $this->assertSame(RequestParameterProvider::provide($request, 'foo'), 'bar_attribute');
    }

    /** @test */
    public function it_returns_request_query_parameter_value(): void
    {
        $request = new Request(['foo' => 'bar_query'], ['foo' => 'bar_request'], ['foo_attribute' => 'bar_attribute']);

        $this->assertSame(RequestParameterProvider::provide($request, 'foo'), 'bar_query');
    }

    /** @test */
    public function it_returns_request_request_parameter_value(): void
    {
        $request = new Request(['foo_query' => 'bar_query'], ['foo' => 'bar_request'], ['foo_attribute' => 'bar_attribute']);

        $this->assertSame(RequestParameterProvider::provide($request, 'foo'), 'bar_request');
    }

    /** @test */
    public function it_returns_null_or_default_value_if_parameter_is_not_set(): void
    {
        $request = new Request(['foo_query' => 'bar_query'], ['foo_request' => 'bar_request'], ['foo_attribute' => 'bar_attribute']);

        $this->assertSame(RequestParameterProvider::provide($request, 'foo'), null);
        $this->assertSame(RequestParameterProvider::provide($request, 'foo', 'default'), 'default');
    }
}
