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

namespace Sylius\Bundle\ResourceBundle\Context\Option;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

final class RequestConfigurationOption
{
    public function __construct(private RequestConfiguration $requestConfiguration)
    {
    }

    public function requestConfiguration(): RequestConfiguration
    {
        return $this->requestConfiguration;
    }
}
