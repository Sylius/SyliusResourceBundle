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

namespace App\Subscription\Twig\Context\Factory;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Twig\Context\Factory\ContextFactoryInterface;

final class ShowSubscriptionContextFactory implements ContextFactoryInterface
{
    public function __construct(private ContextFactoryInterface $decorated)
    {
    }

    public function create(mixed $data, Operation $operation, Context $context): array
    {
        return array_merge($this->decorated->create($data, $operation, $context), [
            'foo' => 'bar',
        ]);
    }
}
