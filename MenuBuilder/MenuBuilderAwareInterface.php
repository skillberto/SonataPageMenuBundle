<?php
/**
 * Created by PhpStorm.
 * User: pentalab_2
 * Date: 2015.01.03.
 * Time: 23:29
 */

namespace Skillberto\SonataPageMenuBundle\MenuBuilder;

interface MenuBuilderAwareInterface {
    public function setMenuBuilder(MenuBuilderInterface $menuBuilderInterface);
}