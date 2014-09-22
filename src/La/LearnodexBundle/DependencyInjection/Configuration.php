<?php

namespace La\LearnodexBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('la_learnodex');

        $rootNode
            ->children()
                ->arrayNode(('random_card_providers'))->addDefaultsIfNotSet()->children()
                    ->arrayNode('non_random_card_provider')->addDefaultsIfNotSet()->children()
                        ->integerNode('non_random_card_id')->defaultValue('7')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
