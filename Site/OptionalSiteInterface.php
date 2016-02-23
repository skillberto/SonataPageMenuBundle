<?php


namespace Skillberto\SonataPageMenuBundle\Site;

use Skillberto\SonataPageMenuBundle\Exception\SiteNotFoundException;
use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Model\SiteManagerInterface;

interface OptionalSiteInterface
{
    /**
     * Return the current site
     *
     * @return SiteInterface
     *
     * @throws SiteNotFoundException
     */
    public function getChosenSite();

    /**
     * Return the 'original' site.
     * In this mean original can be default, or if default does not exist, any located site.
     * If any site does not exist, then throw an exception
     *
     * @return SiteInterface
     *
     * @throws SiteNotFoundException
     */
    public function getOriginalSite();

    /**
     * @return SiteManagerInterface
     */
    public function getSiteManager();
}