<?php
namespace Skillberto\SonataPageMenuBundle\MenuBuilder;

use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sonata\PageBundle\Entity\PageManager;
use Sonata\PageBundle\Route\CmsPageRouter;
use Sonata\PageBundle\Site\SiteSelectorInterface;
use Symfony\Cmf\Component\Routing\ChainedRouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class MainMenuBuilder implements MenuBuilderInterface
{
    protected
        $menuEntity,
        $factoryInterface,
        $managerRegistry,
        $requestStack,
        $routerInterface,
        $siteSelectorInterface,
        $currentMenuName = null,
        $rendered = false,
        $mainMenu,
        $request;

    public function __construct($menuEntity, FactoryInterface $factoryInterface, ManagerRegistry $managerRegistry, RequestStack $requestStack, ChainedRouterInterface $routerInterface, SiteSelectorInterface $siteSelectorInterface)
    {
        $this->menuEntity               = $menuEntity;
        $this->factoryInterface         = $factoryInterface;
        $this->managerRegistry          = $managerRegistry;
        $this->requestStack             = $requestStack;
        $this->routerInterface          = $routerInterface;
        $this->siteSelectorInterface    = $siteSelectorInterface;
    }
    
    public function getMenu()
    {
        if ($this->rendered === false) {
            $this->renderMenu();

            $this->rendered = true;
        }

        return $this->mainMenu;
    }

    public function getCurrentMenuName()
    {
        return $this->currentMenuName;
    }

    protected function renderMenu()
    {
        $site    = $this->siteSelectorInterface->retrieve();
        $menus   = $this->managerRegistry->getRepository($this->menuEntity)->findBy(array("active" => 1, "site" => $site->getId(), "parent" => null), array("position" => "ASC"));

        $this->request = $this->requestStack->getCurrentRequest();
        $this->mainMenu = $this->factoryInterface->createItem('root');
        $this->mainMenu->setChildrenAttributes(array("class" => "nav sf-menu clearfix sf-js-enabled"));

        $this->putRootAttributes($this->mainMenu);
        $this->createMenuStructure($menus, $this->mainMenu);
    }

    protected function createMenuStructure($menus, ItemInterface $root)
    {
        $this->checkArgument($menus);

        foreach ($menus as $menu) {
            $this->createMenu($menu, $root);
        }
    }

    protected function checkArgument($menus)
    {
        if ((! $menus instanceof \ArrayAccess ) && (! is_array($menus))) {
            throw new \InvalidArgumentException(sprintf("CreateMenu first argument must be an array or instance of ArrayAccess, %s given", gettype($menus)));
        }
    }

    protected function createMenu($menu, ItemInterface $root)
    {
        $currentItem = $this->createMenuItem($menu);

        $root->addChild($currentItem);

        if (count($menu->getChildren()) > 0) {
            $this->createChildMenu($menu->getChildren(), $currentItem);
        }
    }

    protected function createMenuItem($menu)
    {
        $current = $this->factoryInterface->createItem($menu->getName(), array('label' => $menu->getName()));

        if ($menu->getClickable()) {
            $this->createLink($current, $menu);
        }

        if ($this->request->get('page') && $menu->getPage()->getId() == $this->request->get('page')->getId()) {
            $current->setCurrent(true);
            $this->currentMenuName = $menu->getName();
        }

        return $current;
    }

    protected function createChildMenu(\ArrayAccess $children, ItemInterface $currentItem)
    {
        $this->putChildAttributes($currentItem);
        $this->createMenuStructure($children, $currentItem);
    }

    protected function createLink(ItemInterface $itemInterface, $menu)
    {
        $uri = $this->routerInterface->generate($menu->getPage());

        $itemInterface->setUri($uri);
    }

    protected function putRootAttributes(ItemInterface $itemInterface)
    {
        $itemInterface->setChildrenAttribute("class", "nav sf-menu clearfix sf-js-enabled");
    }

    protected function putChildAttributes(ItemInterface $itemInterface)
    {
        $itemInterface->setAttribute("class", "sub-menu");
    }
}