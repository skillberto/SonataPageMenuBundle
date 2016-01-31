<?php

namespace Skillberto\SonataPageMenuBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class SkillbertoSonataPageMenuExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container) {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('site.xml');
        $loader->load('admin.xml');
        $loader->load('menu.xml');
    }
}
