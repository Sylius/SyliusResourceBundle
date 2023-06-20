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

namespace Sylius\Bundle\ResourceBundle\Twig\Context;

use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\MetadataOption;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Twig\Context\Factory\ContextFactoryInterface;

final class LegacyContextFactory implements ContextFactoryInterface
{
    public function __construct(private ContextFactoryInterface $decorated)
    {
    }

    public function create(mixed $data, Operation $operation, Context $context): array
    {
        $twigContext = $this->decorated->create($data, $operation, $context);

        $requestConfiguration = $context->get(RequestConfigurationOption::class)?->requestConfiguration();
        $metadata = $context->get(MetadataOption::class)?->metadata();

        if (null !== $requestConfiguration) {
            $twigContext['configuration'] = $requestConfiguration;
        }

        if (null !== $metadata) {
            $twigContext['metadata'] = $metadata;
        }

        return $twigContext;
    }
}
