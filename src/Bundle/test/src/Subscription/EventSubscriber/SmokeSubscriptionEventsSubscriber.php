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

namespace App\Subscription\EventSubscriber;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SmokeSubscriptionEventsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'app.subscription.show' => 'smokeShowEvent',
            'app.subscription.index' => 'smokeIndexEvent',
            'app.subscription.pre_create' => 'smokePreEvent',
            'app.subscription.post_create' => 'smokePostEvent',
            'app.subscription.pre_update' => 'smokePreEvent',
            'app.subscription.post_update' => 'smokePostEvent',
            'app.subscription.pre_delete' => 'smokePreEvent',
            'app.subscription.post_delete' => 'smokePostEvent',
            'app.subscription.bulk_delete' => 'smokeBulkEvent',
        ];
    }

    public function smokeShowEvent(OperationEvent $event): void
    {
    }

    public function smokeIndexEvent(ResourceControllerEvent $event): void
    {
    }

    public function smokePreEvent(OperationEvent $event): void
    {
    }

    public function smokePostEvent(OperationEvent $event): void
    {
    }

    public function smokeBulkEvent(OperationEvent $event): void
    {
    }
}
