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

namespace Sylius\Bundle\ResourceBundle\Creator;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Symfony\Component\HttpFoundation\Response;

interface RestViewCreatorInterface
{
    public function createRestView(RequestConfiguration $configuration, mixed $data, int $statusCode = null): Response;
}
