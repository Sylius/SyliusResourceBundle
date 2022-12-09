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

namespace Sylius\Component\Resource\Metadata\Factory\Operation;

use Sylius\Component\Resource\Metadata\Operation;

interface OperationFactoryInterface
{
    /**
     * @psalm-param class-string $operationClass
     */
    public function create(string $operationClass, array $options): Operation;
}
