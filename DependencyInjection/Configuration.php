<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dunglas_angular_csrf');
        $rootNode
            ->children()
                ->arrayNode('token')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('id')->cannotBeEmpty()->defaultValue('angular')->end()
                    ->end()
                ->end()
                ->arrayNode('header')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->cannotBeEmpty()->defaultValue('X-XSRF-TOKEN')->end()
                    ->end()
                ->end()
                ->arrayNode('cookie')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->cannotBeEmpty()->defaultValue('XSRF-TOKEN')->end()
                        ->integerNode('expire')->defaultValue(0)->end()
                        ->scalarNode('path')->cannotBeEmpty()->defaultValue('/')->end()
                        ->scalarNode('domain')->cannotBeEmpty()->defaultValue(null)->end()
                        ->booleanNode('secure')->defaultFalse()->end()
                        ->arrayNode('set_on')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('path')->defaultFalse()->end()
                                    ->scalarNode('route')->defaultFalse()->end()
                                    ->scalarNode('host')->defaultFalse()->end()
                                    ->arrayNode('methods')->prototype('scalar')->end()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('secure')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')->defaultFalse()->end()
                            ->scalarNode('route')->defaultFalse()->end()
                            ->scalarNode('host')->defaultFalse()->end()
                            ->arrayNode('methods')->prototype('scalar')->end()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('exclude')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')->defaultFalse()->end()
                            ->scalarNode('route')->defaultFalse()->end()
                            ->scalarNode('host')->defaultFalse()->end()
                            ->arrayNode('methods')->prototype('scalar')->end()->end()
                        ->end()
                        ->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }
}
