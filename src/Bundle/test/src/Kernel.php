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

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/' . $this->environment . '/*.yaml');

        if (is_file(\dirname(__DIR__) . '/config/services.yaml')) {
            $container->import('../config/{services}.yaml');
            $container->import('../config/{services}_' . $this->environment . '.yaml');
        }

        if (is_file($path = \dirname(__DIR__) . '/config/services.xml')) {
            $container->import('../config/{services}.xml');
            $container->import('../config/{services}_' . $this->environment . '.xml');
        }

//        if (is_file($path = \dirname(__DIR__).'/config/services.php')) {
//            (require $path)($container->withPath($path), $this);
//        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/' . $this->environment . '/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (is_file(\dirname(__DIR__) . '/config/routes.yaml')) {
            $routes->import('../config/{routes}.yaml');
        } elseif (is_file($path = \dirname(__DIR__) . '/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }
}
