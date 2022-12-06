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

namespace Sylius\Component\Resource\Context\Option;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

class RequestConfigurationOption
{
    public function __construct(private RequestConfiguration $configuration)
    {
    }

    public function configuration(): RequestConfiguration
    {
        return $this->configuration;
    }
}
