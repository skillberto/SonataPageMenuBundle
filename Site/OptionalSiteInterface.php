<?php


namespace Skillberto\SonataPageMenuBundle\Site;

use Sonata\PageBundle\Model\Site;
use Sonata\PageBundle\Model\SiteManagerInterface;

interface OptionalSiteInterface
{
    /**
     * @return Site
     */
    public function getChosenSite();

    /**
     * @return Site;
     */
    public function getOriginalSite();

    /**
     * @return SiteManagerInterface
     */
    public function getSiteManager();
}