<?php

namespace Sylius\Component\Resource\Context\Option;

use Symfony\Component\Console\Input\InputInterface;

class InputOption
{
    public function __construct(private InputInterface $input)
    {
    }

    public function input(): InputInterface
    {
        return $this->input;
    }
}