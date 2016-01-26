<?php
/**
 * Created by PhpStorm.
 * User: pentalab_2
 * Date: 2015.01.03.
 * Time: 23:29
 */

namespace Skillberto\SonataPageMenuBundle\MenuBuilder;

use Knp\Menu\ItemInterface;

interface MenuBuilderInterface {
    /**
     * @return ItemInterface
     */
    public function getMenu();

    /**
     * @return null|string
     */
    public function getCurrentMenuName();
}