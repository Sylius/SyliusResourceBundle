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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

@trigger_error(sprintf('The "%s" class is deprecated since Sylius 1.8. Migrate your Pagerfanta configuration from WhiteOctoberPagerfantaBundle to BabDevPagerfantaBundle, the configuration bridge will be removed in Sylius 2.0.', PagerfantaConfiguration::class), \E_USER_DEPRECATED);

/**
 * Container configuration to bridge the configuration from WhiteOctoberPagerfantaBundle to BabDevPagerfantaBundle
 *
 * @internal
 */
final class PagerfantaConfiguration implements ConfigurationInterface
{
    public const EXCEPTION_STRATEGY_TO_HTTP_NOT_FOUND = 'to_http_not_found';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('white_october_pagerfanta');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $this->addExceptionsStrategySection($rootNode);

        $rootNode
            ->children()
                ->scalarNode('default_view')
                    ->defaultValue('default')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function addExceptionsStrategySection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('exceptions_strategy')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('out_of_range_page')->defaultValue(self::EXCEPTION_STRATEGY_TO_HTTP_NOT_FOUND)->end()
                        ->scalarNode('not_valid_current_page')->defaultValue(self::EXCEPTION_STRATEGY_TO_HTTP_NOT_FOUND)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
