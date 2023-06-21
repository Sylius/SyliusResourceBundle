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

namespace Sylius\Component\Resource\Twig\Context\Factory;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;

interface ContextFactoryInterface
{
    public function create(mixed $data, Operation $operation, Context $context): array;
}
