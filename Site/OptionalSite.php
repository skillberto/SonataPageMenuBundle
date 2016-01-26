<?php

namespace Skillberto\SonataPageMenuBundle\Site;

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

    public function getChosenSite()
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if ($siteId = $currentRequest->get('site')) {
            return $this->siteManagerInterface->find($siteId);
        }

        return $this->getOriginalSite();
    }

    public function getOriginalSite()
    {
        return $this->siteSelectorInterface->retrieve();
    }

    public function getSiteManager()
    {
        return $this->siteManagerInterface;
    }
}