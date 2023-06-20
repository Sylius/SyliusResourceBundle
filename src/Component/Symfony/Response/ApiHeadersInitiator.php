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

namespace Sylius\Component\Resource\Symfony\Response;

final class ApiHeadersInitiator implements HeadersInitiatorInterface
{
    public function initializeHeaders(string $mimeType): array
    {
        return [
            'Content-Type' => sprintf('%s; charset=utf-8', $mimeType),
            'Vary' => 'Accept',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'deny',
        ];
    }
}
