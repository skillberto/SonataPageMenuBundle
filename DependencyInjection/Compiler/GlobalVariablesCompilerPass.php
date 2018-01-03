<?php
namespace Skillberto\SonataPageMenuBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Skillberto\SonataPageMenuBundle\DependencyInjection\Configuration;

class GlobalVariablesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig('skillberto_sonata_page_menu');
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $container->getDefinition('twig')
            ->addMethodCall('addGlobal', ['sonata_page_menu', $config]);
    }

    private function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new Processor();
        
        return $processor->processConfiguration($configuration, $configs);
    }
}
