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

namespace App\BoardGameBlog\Infrastructure\Sylius\State\Http\Provider;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Grid\State\RequestGridProvider;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProviderInterface;

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
