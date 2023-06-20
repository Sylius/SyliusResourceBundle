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

namespace App\Tests\Tmp\Sylius\State;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProviderInterface;

final class BookItemProvider implements ProviderInterface
{
    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        // Retrieve the state from somewhere

        return null;
    }
}
