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

use Symfony\Component\Console\Command\Command;

final class ConsoleOption
{
    public function __construct(private Command $command)
    {
    }

    public function command(): Command
    {
        return $this->command;
    }
}
