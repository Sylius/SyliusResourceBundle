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

namespace Sylius\Component\Resource\Util;

use Sylius\Component\Resource\Metadata\Factory\OperationFactoryInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
trait OperationRequestInitiatorTrait
{
    private OperationFactoryInterface $operationFactory;

    private function initializeOperation(Request $request): ?Operation
    {
        $attributes = $request->attributes->all('_sylius');

        if (
            [] === $attributes ||
            null === ($attributes['resource'] ?? null)
        ) {
            return null;
        }

        return $this->operationFactory?->createFromRequest($request);
    }
}
