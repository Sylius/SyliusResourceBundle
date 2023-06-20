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

namespace Sylius\Component\Resource\Symfony\ExpressionLanguage;

use Symfony\Component\HttpFoundation\RequestStack;

final class RequestVariables implements VariablesInterface
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function getVariables(): array
    {
        return [
            'request' => $this->requestStack->getCurrentRequest(),
        ];
    }
}
