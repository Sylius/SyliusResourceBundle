<?php

namespace Sylius\Component\Resource\Context\Option;

use Symfony\Component\HttpFoundation\Request;

class RequestOption
{
    public function __construct(private Request $request)
    {
    }

    public function request(): Request
    {
        return $this->request;
    }
}