<?php

namespace Aldaflux\GameQuizzBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('aldaflux_game_quizz');
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('aldaflux_game_quizz');
        }
         
        
$rootNode->children()
        ->arrayNode('fields')
            ->children()
                ->booleanNode('youtube')->defaultFalse()->end()
                ->booleanNode('mpg')->defaultFalse()->end()
            ->end()
        ->end()
    ->end();

$rootNode->children()
        ->arrayNode('folders')
            ->children()
                ->scalarNode('public')->end()
                ->scalarNode('audio')->end()
                ->scalarNode('video')->end() 
            ->end()
        ->end()
    ->end()
;
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
