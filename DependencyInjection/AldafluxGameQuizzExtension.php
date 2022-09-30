<?php

namespace Aldaflux\GameQuizzBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class AldafluxGameQuizzExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter( 'abcd', "abcd" );

        $container->setParameter( 'aldaflux_game_quizz.fields', $config[ 'fields' ] );
        $container->setParameter( 'aldaflux_game_quizz.folders', $config[ 'folders' ] );
        
        
 
        $container->setParameter( 'aldaflux_game_quizz.folder_video', $config[ 'folders' ]["video"] );
        $container->setParameter( 'aldaflux_game_quizz.folder_audio', $config[ 'folders' ]["audio"] );
        $container->setParameter( 'aldaflux_game_quizz.folder_public', $config[ 'folders' ]["public"] );
        
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
