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

namespace spec\Sylius\Bundle\ResourceBundle\Context\Option;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

final class RequestConfigurationOptionSpec extends ObjectBehavior
{
    function let(RequestConfiguration $requestConfiguration): void
    {
        $this->beConstructedWith($requestConfiguration);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RequestConfigurationOption::class);
    }

    function it_returns_request_configuration(RequestConfiguration $requestConfiguration): void
    {
        $this->requestConfiguration()->shouldReturn($requestConfiguration);
    }
}
