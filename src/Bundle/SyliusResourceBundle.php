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

namespace Sylius\Bundle\ResourceBundle;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\DoctrineContainerRepositoryFactoryPass;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\DoctrineTargetEntitiesResolverPass;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\Helper\TargetEntitiesResolver;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\RegisterFqcnControllersPass;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\RegisterStateMachinePass;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\WinzouStateMachinePass;
use Sylius\Bundle\ResourceBundle\DependencyInjection\PagerfantaExtension;
use Sylius\Component\Resource\Symfony\Bundle\DependencyInjection\Compiler\CsrfTokenManagerPass;
use Sylius\Component\Resource\Symfony\Bundle\DependencyInjection\Compiler\PagerfantaBridgePass;
use Sylius\Component\Resource\Symfony\Bundle\DependencyInjection\Compiler\RegisterFormBuilderPass;
use Sylius\Component\Resource\Symfony\Bundle\DependencyInjection\Compiler\RegisterResourceRepositoryPass;
use Sylius\Component\Resource\Symfony\Bundle\DependencyInjection\Compiler\RegisterResourcesPass;
use Sylius\Component\Resource\Symfony\Bundle\DependencyInjection\Compiler\RegisterResourceStateMachinePass;
use Sylius\Component\Resource\Symfony\Bundle\DependencyInjection\Compiler\TwigPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SyliusResourceBundle extends Bundle
{
    public const DRIVER_DOCTRINE_ORM = 'doctrine/orm';

    public const DRIVER_DOCTRINE_MONGODB_ODM = 'doctrine/mongodb-odm';

    public const DRIVER_DOCTRINE_PHPCR_ODM = 'doctrine/phpcr-odm';

    public const NO_DRIVER = false;

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CsrfTokenManagerPass());
        $container->addCompilerPass(new DoctrineContainerRepositoryFactoryPass());
        $container->addCompilerPass(new DoctrineTargetEntitiesResolverPass(new TargetEntitiesResolver()), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1);
        $container->addCompilerPass(new RegisterFormBuilderPass());
        $container->addCompilerPass(new RegisterFqcnControllersPass());
        $container->addCompilerPass(new RegisterResourceRepositoryPass());
        $container->addCompilerPass(new RegisterResourcesPass());
        $container->addCompilerPass(new RegisterStateMachinePass());
        $container->addCompilerPass(new RegisterResourceStateMachinePass());
        $container->addCompilerPass(new TwigPass());
        $container->addCompilerPass(new WinzouStateMachinePass());

        $container->registerExtension(new PagerfantaExtension(true));
        $container->addCompilerPass(new PagerfantaBridgePass(true), PassConfig::TYPE_BEFORE_OPTIMIZATION, -1); // Should run after all passes from BabDevPagerfantaBundle
    }

    /**
     * @return string[]
     */
    public static function getAvailableDrivers(): array
    {
        return [
            self::DRIVER_DOCTRINE_ORM,
            self::DRIVER_DOCTRINE_MONGODB_ODM,
            self::DRIVER_DOCTRINE_PHPCR_ODM,
        ];
    }
}
