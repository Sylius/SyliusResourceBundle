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

use Symfony\Component\Console\Output\OutputInterface;

final class OutputOption
{
    public function __construct(private OutputInterface $output)
    {
    }

    public function output(): OutputInterface
    {
        return $this->output;
    }
}
