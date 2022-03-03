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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine\DoctrineODMDriver;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine\DoctrineORMDriver;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine\DoctrinePHPCRDriver;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\DriverProvider;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Metadata\Metadata;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SyliusResourceExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.xml');

        /** @var array $bundles */
        $bundles = $container->getParameter('kernel.bundles');
        if (array_key_exists('SyliusGridBundle', $bundles)) {
            $loader->load('services/integrations/grid.xml');
        }

        if ($config['translation']['enabled']) {
            $loader->load('services/integrations/translation.xml');

            $container->setAlias('sylius.translation_locale_provider', $config['translation']['locale_provider'])->setPublic(true);
            $this->createTranslationParameters($config, $container);
        }

        $container->setParameter('sylius.resource.mapping', $config['mapping']);
        $container->setParameter('sylius.resource.settings', $config['settings']);
        $container->setAlias('sylius.resource_controller.authorization_checker', $config['authorization_checker']);

        $this->loadPersistence($config['drivers'], $config['resources'], $loader);
        $this->loadResources($config['resources'], $container);

        $container->addObjectResource(Metadata::class);
        $container->addObjectResource(DriverProvider::class);
        $container->addObjectResource(DoctrineORMDriver::class);
        $container->addObjectResource(DoctrineODMDriver::class);
        $container->addObjectResource(DoctrinePHPCRDriver::class);
    }

    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        $configuration = new Configuration();

        $container->addObjectResource($configuration);

        return $configuration;
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = ['body_listener' => ['enabled' => true]];
        $container->prependExtensionConfig('fos_rest', $config);
    }

    private function loadPersistence(array $drivers, array $resources, LoaderInterface $loader): void
    {
        $integrateDoctrine = array_reduce($drivers, function (bool $result, string $driver): bool {
            return $result || in_array($driver, [SyliusResourceBundle::DRIVER_DOCTRINE_ORM, SyliusResourceBundle::DRIVER_DOCTRINE_PHPCR_ODM, SyliusResourceBundle::DRIVER_DOCTRINE_MONGODB_ODM], true);
        }, false);

        if ($integrateDoctrine) {
            $loader->load('services/integrations/doctrine.xml');
        }

        foreach ($resources as $alias => $resource) {
            if (!in_array($resource['driver'], $drivers, true)) {
                throw new InvalidArgumentException(sprintf(
                    'Resource "%s" uses driver "%s", but this driver has not been enabled.',
                    $alias,
                    $resource['driver']
                ));
            }
        }

        foreach ($drivers as $driver) {
            if (in_array($driver, [SyliusResourceBundle::DRIVER_DOCTRINE_PHPCR_ODM, SyliusResourceBundle::DRIVER_DOCTRINE_MONGODB_ODM], true)) {
                @trigger_error(sprintf(
                    'The "%s" driver is deprecated in Sylius 1.3. Doctrine MongoDB and PHPCR will no longer be supported in Sylius 2.0.',
                    $driver
                ), \E_USER_DEPRECATED);
            }

            $loader->load(sprintf('services/integrations/%s.xml', $driver));
        }
    }

    private function loadResources(array $loadedResources, ContainerBuilder $container): void
    {
        /** @var array<string, array> $resources */
        $resources = $container->hasParameter('sylius.resources') ? $container->getParameter('sylius.resources') : [];

        foreach ($loadedResources as $alias => $resourceConfig) {
            $metadata = Metadata::fromAliasAndConfiguration($alias, $resourceConfig);

            $resources[$alias] = $resourceConfig;
            $container->setParameter('sylius.resources', $resources);

            DriverProvider::get($metadata)->load($container, $metadata);

            if ($metadata->hasParameter('translation')) {
                $alias .= '_translation';
                $resourceConfig = array_merge(['driver' => $resourceConfig['driver']], $resourceConfig['translation']);

                $resources[$alias] = $resourceConfig;
                $container->setParameter('sylius.resources', $resources);

                $metadata = Metadata::fromAliasAndConfiguration($alias, $resourceConfig);

                DriverProvider::get($metadata)->load($container, $metadata);
            }
        }
    }

    private function createTranslationParameters(array $config, ContainerBuilder $container): void
    {
        $this->createEnabledLocalesParameter($config, $container);
        $this->createDefaultLocaleParameter($config, $container);
    }

    private function createEnabledLocalesParameter(array $config, ContainerBuilder $container): void
    {
        $enabledLocales = $config['translation']['enabled_locales'];

        if (count($enabledLocales) > 0) {
            $container->setParameter('sylius.resource.translation.enabled_locales', $enabledLocales);

            return;
        }

        if ($container->hasParameter('locale')) {
            trigger_deprecation('sylius/resource-bundle', '1.9', 'Locale parameter usage to defined the enabled locales will no longer used in 2.0, you should use %kernel.enabled_locales% instead.');

            $container->setParameter('sylius.resource.translation.enabled_locales', [$container->getParameter('locale')]);

            return;
        }

        if ($container->hasParameter('kernel.enabled_locales')) {
            $kernelEnabledLocales = (array) $container->getParameter('kernel.enabled_locales');

            if (count($kernelEnabledLocales) > 0) {
                $container->setParameter('sylius.resource.translation.enabled_locales', $container->getParameter('kernel.enabled_locales'));

                return;
            }
        }

        $container->setParameter('sylius.resource.translation.enabled_locales', ['en']);
    }

    private function createDefaultLocaleParameter(array $config, ContainerBuilder $container): void
    {
        $defaultLocale = $config['translation']['default_locale'];

        if (is_string($defaultLocale)) {
            $container->setParameter('sylius.resource.translation.default_locale', $defaultLocale);

            return;
        }

        if ($container->hasParameter('locale')) {
            trigger_deprecation('sylius/resource-bundle', '1.9', 'Locale parameter usage to define the translation default locale will no longer used in 2.0, you should use %kernel.default_locale% instead.');

            $container->setParameter('sylius.resource.translation.default_locale', $container->getParameter('locale'));

            return;
        }

        if ($container->hasParameter('kernel.default_locale')) {
            $kernelDefaultLocale = $container->getParameter('kernel.default_locale');

            if (is_string($kernelDefaultLocale)) {
                $container->setParameter('sylius.resource.translation.default_locale', $kernelDefaultLocale);

                return;
            }
        }

        $container->setParameter('sylius.resource.translation.default_locale', 'en');
    }
}
