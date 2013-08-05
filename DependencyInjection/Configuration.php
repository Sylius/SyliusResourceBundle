<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ResourceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sylius_resource');

        $this->addSettingsSection($rootNode);
        $this->addDynamicsSettingsSection($rootNode);
        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds `resources` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addResourcesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('driver')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('templates')->cannotBeEmpty()->end()
                            ->arrayNode('classes')
                                ->children()
                                    ->scalarNode('model')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue('Sylius\Bundle\ResourceBundle\Controller\ResourceController')->end()
                                    ->scalarNode('repository')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Adds `settings` section.
     *
     * @param $node
     */
    private function addSettingsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('settings')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('limit')->defaultValue(10)->end()
                        ->scalarNode('paginate')->defaultValue(10)->end()
                        ->scalarNode('filterable')->defaultValue(false)->end()
                        ->scalarNode('criteria')->defaultValue(array())->end()
                        ->scalarNode('sortable')->defaultValue(false)->end()
                        ->scalarNode('sorting')->defaultValue(array())->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Adds `dynamic_setting` section.
     *
     * @param $node
     */
    private function addDynamicsSettingsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('default_settings')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('paginate')->defaultValue(10)->end()
                        ->scalarNode('criteria')->defaultValue(array())->end()
                        ->scalarNode('sorting')->defaultValue(array())->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
