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

namespace Sylius\Component\Resource\Symfony\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class StubCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (new SymfonyStyle($input, $output))
            ->error(
                \sprintf("To run \"%s\" you need the \"%s\" which is currently not installed.\n\nTry running \"composer require %s\".", static::$defaultName ?? '', 'MakerBundle', 'symfony/maker-bundle --dev'),
            )
        ;

        return 0;
    }
}
