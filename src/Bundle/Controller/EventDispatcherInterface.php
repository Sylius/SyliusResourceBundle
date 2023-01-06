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

namespace Sylius\Bundle\ResourceBundle\Controller;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Symfony\HttpFoundation\Request\RequestConfiguration as ComponentRequestConfiguration;

interface EventDispatcherInterface
{
    public function dispatch(
        string $eventName,
        ComponentRequestConfiguration $requestConfiguration,
        ResourceInterface $resource,
    ): ResourceControllerEvent;

    /** @param mixed $resources */
    public function dispatchMultiple(
        string $eventName,
        ComponentRequestConfiguration $requestConfiguration,
        $resources,
    ): ResourceControllerEvent;

    public function dispatchPreEvent(
        string $eventName,
        ComponentRequestConfiguration $requestConfiguration,
        ResourceInterface $resource,
    ): ResourceControllerEvent;

    public function dispatchPostEvent(
        string $eventName,
        ComponentRequestConfiguration $requestConfiguration,
        ResourceInterface $resource,
    ): ResourceControllerEvent;

    public function dispatchInitializeEvent(
        string $eventName,
        ComponentRequestConfiguration $requestConfiguration,
        ResourceInterface $resource,
    ): ResourceControllerEvent;
}
