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
