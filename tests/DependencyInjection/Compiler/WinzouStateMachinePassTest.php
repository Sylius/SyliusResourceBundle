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

namespace Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\Assert;
use SM\Callback\CallbackFactoryInterface;
use SM\Callback\CascadeTransitionCallback;
use SM\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class WinzouStateMachinePassTest extends WebTestCase
{
    /** @test */
    public function all_winzou_services_are_public(): void
    {
        if (!class_exists(FactoryInterface::class)) {
            self::markTestSkipped('State machine is not installed');
        }

        /** @var ContainerBuilder $container */
        $container = self::createClient()->getContainer();

        $services = [
            'sm.factory',
            'sm.callback_factory',
            'sm.callback.cascade_transition',
            FactoryInterface::class,
            CallbackFactoryInterface::class,
            CascadeTransitionCallback::class,
        ];

        foreach ($services as $id) {
            Assert::assertNotNull(
                $container->get($id, ContainerInterface::NULL_ON_INVALID_REFERENCE),
                sprintf('Service "%s" could not be found', $id)
            );
        }
    }
}
