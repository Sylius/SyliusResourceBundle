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

namespace Sylius\Bundle\ResourceBundle\Tests\Context\Option;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

final class RequestConfigurationOptionTest extends TestCase
{
    private RequestConfiguration|MockObject $requestConfigurationMock;

    private RequestConfigurationOption $requestConfigurationOption;

    protected function setUp(): void
    {
        $this->requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        $this->requestConfigurationOption = new RequestConfigurationOption($this->requestConfigurationMock);
    }

    function testReturnsRequestConfiguration(): void
    {
        $this->assertSame($this->requestConfigurationMock, $this->requestConfigurationOption->requestConfiguration());
    }
}
