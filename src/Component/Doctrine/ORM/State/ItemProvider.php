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

namespace Sylius\Component\Resource\Doctrine\ORM\State;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\MetadataOption;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ItemProvider implements ProviderInterface
{
    public function __construct(
        private SingleResourceProviderInterface $singleResourceProvider,
    ) {
    }

    public function provide(Operation $operation, Context $context): ?object
    {
        $metadata = $context->get(MetadataOption::class)->metadata();

        if (null === $metadata) {
            throw new \RuntimeException('Metadata was not found on the context');
        }

        if (null === $resource = $this->singleResourceProvider->provide($operation, $context)) {
            throw new NotFoundHttpException(sprintf('The "%s" has not been found', $metadata->getHumanizedName()));
        }

        return $resource;
    }
}
