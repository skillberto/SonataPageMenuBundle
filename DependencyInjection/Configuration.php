<?php
namespace Skillberto\SonataPageMenuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('skillberto_sonata_page_menu','array');
        
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('bootstrap_options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('navbar_brand')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('displayed')
                                    ->defaultTrue()
                                    ->info('Display the navbar brand on the left side or not.')
                                ->end()
                                ->scalarNode('title')
                                    ->defaultValue('My company')
                                    ->info('Title of the the navbar brand, could also be an image tag.')
                                ->end()
                                ->scalarNode('mobile_text')
                                    ->defaultValue('Browse')
                                    ->info('Text displayed to mobile devices when menu is collapsed.')
                                ->end()
                                ->scalarNode('link_path')
                                    ->defaultValue('#')
                                    ->info('Path to use on the navbar brand link.')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->enumNode('template')
                    ->values(array('standard', 'bootstrap'))
                    ->defaultValue('standard')
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;
        
        return $treeBuilder;
    }
}
