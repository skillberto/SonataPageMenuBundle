<?php

namespace Skillberto\SonataPageMenuBundle\Controller;

use Skillberto\AdminBundle\Controller\CRUDController as Controller;

class MenuAdminController extends Controller
{
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render('SkillbertoSonataPageMenuBundle:MenuAdmin:list.html.twig', array(
            'action' => 'list',
            'sites'  => $this->getSites(),
            'currentSite' => $this->getCurrentSite(),
            'datagrid'    => $datagrid,
            'form'        => $formView,
            'csrf_token'  => $this->getCsrfToken('sonata.batch'),
        ));
    }

    /**
     * @return \Sonata\PageBundle\Model\Site
     */
    protected function getCurrentSite()
    {
        $optionalSite = $this->container->get('skillberto.sonatamenu.site.optional');

        return $optionalSite->getChosenSite();
    }

    /**
     * @return array
     */
    protected function getSites()
    {
        return $this->container->get('sonata.page.manager.site')->findBy(array());
    }
}