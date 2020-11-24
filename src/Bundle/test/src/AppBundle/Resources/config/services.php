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

use AppBundle\Service\FirstAutowiredService;
use AppBundle\Service\NoInterfaceAutowiredService;
use AppBundle\Service\SecondAutowiredService;

$container->autowire(FirstAutowiredService::class)->setPublic(true);
$container->autowire(SecondAutowiredService::class)->setPublic(true);
$container->autowire(NoInterfaceAutowiredService::class)->setPublic(true);
