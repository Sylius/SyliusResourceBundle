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

use FOS\RestBundle\View\View;
use Sylius\Component\Resource\Symfony\HttpFoundation\Request\RequestConfiguration as ComponentRequestConfiguration;
use Symfony\Component\HttpFoundation\Response;

interface ViewHandlerInterface
{
    public function handle(ComponentRequestConfiguration $requestConfiguration, View $view): Response;
}
