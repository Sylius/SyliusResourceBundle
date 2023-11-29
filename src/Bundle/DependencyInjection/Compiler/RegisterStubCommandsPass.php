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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Sylius\Component\Resource\Symfony\Command\StubMakeResource;
use Sylius\Component\Resource\Symfony\Maker\MakeResource;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterStubCommandsPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$this->isMakerEnabled($container)) {
            $container->register(StubMakeResource::class)->setClass(StubMakeResource::class)->addTag('console.command');
            $container->removeDefinition('sylius.maker.resource');
            $container->removeAlias(MakeResource::class);
        }
    }

    private function isMakerEnabled(ContainerBuilder $container): bool
    {
        if (!class_exists(MakerBundle::class)) {
            return false;
        }

        /** @var array $bundles */
        $bundles = $container->getParameter('kernel.bundles');

        return in_array(MakerBundle::class, $bundles, true);
    }
}
