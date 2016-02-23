<?php

namespace Skillberto\SonataPageMenuBundle\Site;

use Skillberto\SonataPageMenuBundle\Exception\SiteNotFoundException;
use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Model\SiteManagerInterface;
use Sonata\PageBundle\Site\SiteSelectorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class OptionalSite implements OptionalSiteInterface
{
    protected
        $siteSelectorInterface,
        $siteManagerInterface,
        $requestStack;

    public function __construct(SiteSelectorInterface $siteSelectorInterface, SiteManagerInterface $siteManagerInterface, RequestStack $requestStack)
    {
        $this->siteSelectorInterface = $siteSelectorInterface;
        $this->siteManagerInterface  = $siteManagerInterface;
        $this->requestStack          = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getChosenSite()
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if ($siteId = $currentRequest->get('site')) {
            if ($site = $this->getSiteById($siteId)) {
                return $site;
            }

            throw new SiteNotFoundException(sprintf("Can't find site by %s Site ID.", $siteId));
        }

        return $this->getOriginalSite();
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalSite()
    {
        if ($site = $this->siteSelectorInterface->retrieve()) {
            return $site;
        }

        $sites = $this->getSites();

        if ($site = $this->getDefaultSite($sites)) {
            return $site;
        }

        if (count($sites) == 1) {
            return $sites[0];
        }

        throw new SiteNotFoundException(sprintf("Can't find any site in the system."));
    }

    /**
     * {@inheritdoc}
     */
    public function getSiteManager()
    {
        return $this->siteManagerInterface;
    }

    /**
     * @param  int $siteId
     * @return SiteInterface|null
     */
    protected function getSiteById($siteId)
    {
        return $this->siteManagerInterface->find($siteId);
    }

    /**
     * @return array|null
     */
    protected function getSites()
    {
        return $this->siteManagerInterface->findAll();
    }

    /**
     * @param  SiteInterface[] $sites
     * @return SiteInterface|null
     */
    protected function getDefaultSite(array $sites)
    {
        foreach ($sites as $site) {
            if ($site->getIsDefault()) {
                return $site;
            }
        }

        return null;
    }
}