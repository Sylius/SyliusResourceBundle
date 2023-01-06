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

interface FlashHelperInterface
{
    public function addSuccessFlash(
        ComponentRequestConfiguration $requestConfiguration,
        string $actionName,
        ?ResourceInterface $resource = null,
    ): void;

    public function addErrorFlash(ComponentRequestConfiguration $requestConfiguration, string $actionName): void;

    public function addFlashFromEvent(ComponentRequestConfiguration $requestConfiguration, ResourceControllerEvent $event): void;
}
