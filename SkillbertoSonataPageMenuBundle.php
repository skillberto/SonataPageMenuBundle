<?php

namespace Skillberto\SonataPageMenuBundle;

use Gedmo\Sortable\SortableListener;
use Gedmo\Tree\TreeListener;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SkillbertoSonataPageMenuBundle extends Bundle
{
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