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

namespace Sylius\Component\Resource\Symfony\HttpFoundation\Response;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Symfony\Component\HttpFoundation\Response;

interface RedirectHandlerInterface
{
    public function redirectToResource(Operation $operation, Context $context, object $resource): Response;

    public function redirectToIndex(Operation $operation, Context $context, ?object $resource = null): Response;

    public function redirectToRoute(Operation $operation, Context $context, string $route, array $parameters = []): Response;

    public function redirect(Operation $operation, Context $context, string $url, int $status = 302): Response;

    public function redirectToReferer(Operation $operation, Context $context): Response;
}
