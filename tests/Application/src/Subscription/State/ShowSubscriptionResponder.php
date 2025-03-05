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

namespace App\Subscription\State;

use App\Subscription\Entity\Subscription;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ResponderInterface;
use Sylius\Resource\Symfony\Console\Context\ConsoleOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

final class ShowSubscriptionResponder implements ResponderInterface
{
    /**
     * @param Subscription|mixed $data
     */
    public function respond(mixed $data, Operation $operation, Context $context): int
    {
        $consoleOption = $context->get(ConsoleOption::class);
        Assert::notNull($consoleOption);

        $ui = new SymfonyStyle($consoleOption->input(), $consoleOption->output());

        Assert::isInstanceOf($data, Subscription::class);

        $ui->section('Id');
        $ui->writeln((string) $data->getId());

        $ui->section('State');
        $ui->writeln($data->getState());

        return Command::SUCCESS;
    }
}
