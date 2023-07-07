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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine\DoctrineODMDriver;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine\DoctrineORMDriver;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine\DoctrinePHPCRDriver;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\DriverInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\DefaultResourceType;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sylius_resource');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $this->addResourcesSection($rootNode);
        $this->addSettingsSection($rootNode);
        $this->addTranslationsSection($rootNode);
        $this->addDriversSection($rootNode);

        $rootNode
            ->children()
                ->arrayNode('mapping')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('paths')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('authorization_checker')
                    ->defaultValue('sylius.resource_controller.authorization_checker.disabled')
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
                            ->variableNode('options')->end()
                            ->scalarNode('templates')->cannotBeEmpty()->end()
                            ->scalarNode('state_machine_component')->defaultNull()->end()
                            ->arrayNode('classes')
                                ->isRequired()
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('interface')->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                    ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->scalarNode('form')->defaultValue(DefaultResourceType::class)->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                            ->arrayNode('translation')
                                ->children()
                                    ->variableNode('options')->end()
                                    ->arrayNode('classes')
                                        ->isRequired()
                                        ->addDefaultsIfNotSet()
                                        ->children()
                                            ->scalarNode('model')->isRequired()->cannotBeEmpty()->end()
                                            ->scalarNode('interface')->cannotBeEmpty()->end()
                                            ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                            ->scalarNode('repository')->cannotBeEmpty()->end()
                                            ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                            ->scalarNode('form')->defaultValue(DefaultResourceType::class)->cannotBeEmpty()->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addSettingsSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('settings')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->variableNode('paginate')->defaultNull()->end()
                        ->variableNode('limit')->defaultNull()->end()
                        ->arrayNode('allowed_paginate')
                            ->integerPrototype()->end()
                            ->defaultValue([10, 20, 30])
                        ->end()
                        ->integerNode('default_page_size')->defaultValue(10)->end()
                        ->scalarNode('default_templates_dir')->defaultNull()->end()
                        ->booleanNode('sortable')->defaultFalse()->end()
                        ->variableNode('sorting')->defaultNull()->end()
                        ->booleanNode('filterable')->defaultFalse()->end()
                        ->variableNode('criteria')->defaultNull()->end()
                        ->scalarNode('state_machine_component')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addTranslationsSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('translation')
                    ->canBeDisabled()
                    ->children()
                        ->scalarNode('locale_provider')->defaultValue('sylius.translation_locale_provider.immutable')->cannotBeEmpty()->end()
                ->end()
            ->end()
        ;
    }

    private function addDriversSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('drivers')
                    ->info('The list of enabled drivers with there related class.')
                    ->normalizeKeys(false) // do not replace dashes with underscores
                    ->useAttributeAsKey('type')
                    ->defaultValue([
                        SyliusResourceBundle::DRIVER_DOCTRINE_ORM => ['class' => DoctrineORMDriver::class],
                    ])
                    ->beforeNormalization()
                        ->ifArray()
                        ->then(static function (array $v) {
                            foreach ($v as $driver => $value) {
                                if (isset($value['class'])) {
                                    continue;
                                }
                                // retro-compatibility
                                if (in_array($value, SyliusResourceBundle::getAvailableDrivers(), true)) {
                                    $v[$value] = ['class' => match ($value) {
                                        SyliusResourceBundle::DRIVER_DOCTRINE_ORM => DoctrineORMDriver::class,
                                        SyliusResourceBundle::DRIVER_DOCTRINE_MONGODB_ODM => DoctrineODMDriver::class,
                                        SyliusResourceBundle::DRIVER_DOCTRINE_PHPCR_ODM => DoctrinePHPCRDriver::class,
                                    }];

                                    unset($v[$driver]);

                                    continue;
                                }

                                $v[$driver] = ['class' => $value];
                            }

                            return $v;
                        })
                    ->end()
                    ->validate()
                        ->ifArray()
                        ->then(static function (array $v) {
                            foreach ($v as $driver => $value) {
                                /** @var string|null $class */
                                $class = $value['class'] ?? null;
                                if (null === $class) {
                                    throw new InvalidConfigurationException(sprintf(
                                        'The Sylius Resource driver "%s" must have a class!',
                                        $driver,
                                    ));
                                }

                                if (false === class_exists($class)) {
                                    throw new InvalidConfigurationException(sprintf(
                                        'The Sylius Resource driver "%s" class must be an existing class, "%s" given.',
                                        $driver,
                                        $class,
                                    ));
                                }

                                if (false === is_a($class, DriverInterface::class, true)) {
                                    throw new InvalidConfigurationException(sprintf(
                                        'The Sylius Resource driver "%s" class must be an instance of "%s".',
                                        $driver,
                                        DriverInterface::class,
                                    ));
                                }
                            }

                            return $v;
                        })
                    ->end()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
