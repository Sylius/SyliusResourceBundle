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

namespace Sylius\Component\Resource\Symfony\Routing;

use Sylius\Component\Resource\Metadata\HttpOperation;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @experimental
 */
interface RedirectHandlerInterface
{
    public function redirectToResource(mixed $data, HttpOperation $operation, Request $request): RedirectResponse;

    public function redirectToOperation(mixed $data, HttpOperation $operation, Request $request, string $newOperation): RedirectResponse;

    public function redirectToRoute(mixed $data, string $route, array $parameters = []): RedirectResponse;
}
