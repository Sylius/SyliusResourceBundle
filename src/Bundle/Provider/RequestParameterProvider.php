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

namespace Sylius\Bundle\ResourceBundle\Provider;

use Symfony\Component\HttpFoundation\Request;

final class RequestParameterProvider
{
    public static function provide(Request $request, string $key, mixed $default = null): mixed
    {
        if ($request !== $result = $request->attributes->get($key, $request)) {
            return $result;
        }

        if ($request->query->has($key)) {
            return $request->query->all()[$key];
        }

        if ($request->request->has($key)) {
            return $request->request->all()[$key];
        }

        return $default;
    }
}
