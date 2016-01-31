<?php

namespace Skillberto\SonataPageMenuBundle\Admin;

use Skillberto\SonataPageMenuBundle\Entity\Menu;
use Skillberto\SonataPageMenuBundle\Site\OptionalSiteInterface;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\PageBundle\Model\PageManagerInterface;

abstract class BaseMenuAdmin extends Admin
{
    protected $managerRegistry;

    protected $pageManager;

    protected $optionalSite;

    protected $pageInstance;

    protected $siteInstance;

    protected $formAttribute;

    /**
     * @return Menu
     */
    public function getNewInstance()
    {
        $site = $this->getCurrentSite();

        $instance = parent::getNewInstance();
        $instance->setSite($site);

        return $instance;
    }

    /**
     * @param  string $context
     * @return \Sonata\AdminBundle\Datagrid\ProxyQueryInterface
     */
    public function createBaseQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->andWhere(
            $query->expr()->eq($query->getRootAlias() . '.site', ':site')
        );

        $query->addOrderBy($query->getRootAlias() . '.root', 'ASC');
        $query->addOrderBy($query->getRootAlias() . '.lft', 'ASC');
        $query->setParameter('site', $this->getCurrentSite());

        return $query;
    }

    /**
     * @param  PageManagerInterface $pageManager
     * @return $this
     */
    public function setPageManager(PageManagerInterface $pageManager)
    {
        $this->pageManager = $pageManager;

        return $this;
    }

    /**
     * @param  OptionalSiteInterface $optionalSite
     * @return $this
     */
    public function setOptionalSite(OptionalSiteInterface $optionalSite)
    {
        $this->optionalSite = $optionalSite;

        if ($this->getSubject() && $this->getSubject()->getId()) {
            $this->initializeEditForm();
        } else {
            $this->initializeCreateForm();
        }

        return $this;
    }

    /**
     * @return OptionalSiteInterface
     */
    public function getOptionalSite()
    {
        return $this->optionalSite;
    }

    /**
     * @return PageManagerInterface
     */
    public function getPageManager()
    {
        return $this->pageManager;
    }

    /**
     * @return \Sonata\PageBundle\Model\Site
     */
    public function getCurrentSite()
    {
        return $this->getOptionalSite()->getChosenSite();
    }

    /**
     * @return array|null
     */
    public function getSites()
    {
        return $this->getOptionalSite()->getSiteManager()->findBy(array());
    }

    /**
     * @return array
     */
    protected function getAllPages()
    {
        $currentSite = $this->getCurrentSite();

        if ($currentSite) {
            $pages = $this->pageManager->loadPages($currentSite);
        } else {
            $pages = array();
        }

        return $pages;
    }

    protected function initializeEditForm()
    {
    }

    protected function initializeCreateForm()
    {
    }
}