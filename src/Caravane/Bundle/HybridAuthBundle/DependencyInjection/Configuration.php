<?php

namespace Caravane\Bundle\HybridAuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('caravane_hybrid_auth');

        $rootNode
            ->children()
            ->scalarNode('base_url')->defaultValue('caravane_hybrid_auth_endpoint')->end()
             ->scalarNode('success_url')->defaultValue('caravane_hybrid_auth_endpoint')->end()
             ->arrayNode('providers')
                   ->isRequired()
                    ->useAttributeAsKey('name')
                        ->prototype('array')
                        ->children()
                            ->scalarNode('type')->cannotBeEmpty()->end()
                            ->scalarNode('enabled')->defaultValue(true)->end()
                            ->arrayNode('keys')
                            ->children()
                                ->scalarNode('id')->cannotBeEmpty()->end()
                                ->scalarNode('secret')->cannotBeEmpty()->end()
                                ->scalarNode('scope')->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
