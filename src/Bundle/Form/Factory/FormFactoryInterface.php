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

namespace Sylius\Bundle\ResourceBundle\Form\Factory;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Symfony\Component\Form\FormInterface;

interface FormFactoryInterface
{
    public function create(RequestConfiguration $requestConfiguration, ?object $data): FormInterface;
}
