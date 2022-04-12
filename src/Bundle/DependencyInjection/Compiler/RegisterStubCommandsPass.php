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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Sylius\Bundle\ResourceBundle\Command\StubMakeResourceTransformer;
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
            $container->register(StubMakeResourceTransformer::class)->setClass(StubMakeResourceTransformer::class)->addTag('console.command');
            $container->removeDefinition('sylius.resource_transformer.maker');
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
