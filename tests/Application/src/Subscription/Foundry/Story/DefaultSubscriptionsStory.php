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

namespace App\Subscription\Foundry\Story;

use App\Subscription\Foundry\Factory\SubscriptionFactory;
use function Zenstruck\Foundry\Persistence\flush_after;
use Zenstruck\Foundry\Story;

final class DefaultSubscriptionsStory extends Story
{
    public function build(): void
    {
        flush_after(function () {
            SubscriptionFactory::new()
                ->withEmail('marty.mcfly@bttf.com')
                ->create()
            ;

            SubscriptionFactory::new()
                ->withEmail('doc.brown@bttf.com')
                ->create()
            ;

            SubscriptionFactory::new()
                ->withEmail('biff.tannen@bttf.com')
                ->accepted()
                ->create()
            ;

            SubscriptionFactory::new()
                ->withEmail('lorraine.baines@bttf.com')
                ->create()
            ;

            SubscriptionFactory::new()
                ->withEmail('george.mcfly@bttf.com')
                ->create()
            ;

            SubscriptionFactory::new()
                ->withEmail('jennifer.parker@bttf.com')
                ->create()
            ;
        });
    }
}
