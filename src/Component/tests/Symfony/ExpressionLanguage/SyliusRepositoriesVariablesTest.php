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

namespace Sylius\Resource\Tests\Symfony\ExpressionLanguage;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sylius\Resource\Symfony\ExpressionLanguage\SyliusRepositoriesVariables;

final class SyliusRepositoriesVariablesTest extends TestCase
{
    public function testItReturnsTheSyliusRepositoryVariables(): void
    {
        $syliusRepositories = $this->createMock(ContainerInterface::class);

        $syliusRepositoriesVariables = new SyliusRepositoriesVariables($syliusRepositories);

        $this->assertSame(['sylius_repositories' => $syliusRepositories], $syliusRepositoriesVariables->getVariables());
    }
}
