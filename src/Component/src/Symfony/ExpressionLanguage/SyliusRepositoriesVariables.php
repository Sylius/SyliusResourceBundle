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

namespace Sylius\Resource\Symfony\ExpressionLanguage;

use Psr\Container\ContainerInterface;

final class SyliusRepositoriesVariables implements VariablesInterface
{
    public function __construct(
        private readonly ContainerInterface $repositories,
    ) {
    }

    public function getVariables(): array
    {
        return [
            'sylius_repositories' => $this->repositories,
        ];
    }
}
