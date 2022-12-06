<?php

namespace Sylius\Component\Resource\Context\Option;

use Symfony\Component\Console\Output\OutputInterface;

class OutputOption
{
    public function __construct(private OutputInterface $output)
    {
    }

    public function output(): OutputInterface
    {
        return $this->output;
    }
}