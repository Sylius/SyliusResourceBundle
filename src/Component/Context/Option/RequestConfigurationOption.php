<?php

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