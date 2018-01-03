<?php

namespace Skillberto\SonataPageMenuBundle;

use Gedmo\Sortable\SortableListener;
use Gedmo\Tree\TreeListener;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Skillberto\SonataPageMenuBundle\DependencyInjection\Compiler\GlobalVariablesCompilerPass;

class SkillbertoSonataPageMenuBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new GlobalVariablesCompilerPass());
    }
    
    public function boot()
    {
        $treeListener = new TreeListener();
        $sortableListener = new SortableListener();

        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $evm = $em->geteventmanager();
        $evm->addeventsubscriber($treeListener);
        //$evm->addeventsubscriber($sortableListener);
    }
}
