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

namespace Sylius\Resource\State\Provider;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\CreateOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ReadProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $provider,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        $request = $context->get(RequestOption::class)?->request();

        if (
            $operation instanceof CreateOperationInterface ||
            !($operation->canRead() ?? true)
        ) {
            return null;
        }

        $data = $this->provider->provide($operation, $context);

        if (null === $data) {
            throw new NotFoundHttpException('Resource has not been found.');
        }

        $request?->attributes->set('data', $data);

        return $data;
    }
}
