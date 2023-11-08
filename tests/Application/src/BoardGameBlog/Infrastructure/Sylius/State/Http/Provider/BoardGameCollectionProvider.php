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

namespace App\BoardGameBlog\Infrastructure\Sylius\State\Http\Provider;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Grid\State\RequestGridProvider;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProviderInterface;

final class BoardGameCollectionProvider implements ProviderInterface
{
    public function __construct(private RequestGridProvider $requestGridProvider)
    {
    }

    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        return $this->requestGridProvider->provide($operation, $context);
    }
}
