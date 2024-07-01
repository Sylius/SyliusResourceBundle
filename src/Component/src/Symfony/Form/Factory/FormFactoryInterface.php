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

namespace Sylius\Resource\Symfony\Form\Factory;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Symfony\Component\Form\FormInterface;

/**
 * @experimental
 */
interface FormFactoryInterface
{
    public function create(Operation $operation, Context $context, mixed $data = null): FormInterface;
}
