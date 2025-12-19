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

namespace App;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use FOS\RestBundle\FOSRestBundle;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use winzou\Bundle\StateMachineBundle\winzouStateMachineBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../config'));

        $container->registerForAutoconfiguration(QueryHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'query.bus'])
        ;

        $container->registerForAutoconfiguration(CommandHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'command.bus'])
        ;

        if (self::MAJOR_VERSION < 7) {
            $container->prependExtensionConfig('security', [
                'enable_authenticator_manager' => true,
            ]);
        }

        if (class_exists(Urlizer::class)) {
            $this->configureAppWithGedmoDoctrineExtensions($loader);
        }

        if (class_exists(FosRestBundle::class)) {
            $this->configureAppWithFosRestBundle($loader);
        }

        if (class_exists(winzouStateMachineBundle::class)) {
            $this->configureAppWithWinzouStateMachine($loader, $container);
        }
    }

    private function configureAppWithGedmoDoctrineExtensions(YamlFileLoader $loader): void
    {
        $loader->load('services/integration/gedmo.yaml');
    }

    private function configureAppWithFosRestBundle(YamlFileLoader $loader): void
    {
        $loader->load('integration/fos_rest.yaml');
    }

    private function configureAppWithWinzouStateMachine(YamlFileLoader $loader, ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('sylius_resource', [
            'settings' => [
                'state_machine_component' => 'winzou',
            ],
        ]);

        $loader->load('integration/winzou_state_machine.yaml');
    }
}
