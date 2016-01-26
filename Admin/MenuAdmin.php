<?php

namespace Skillberto\SonataPageMenuBundle\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Util\ClassUtils;
use Sonata\AdminBundle\Admin\Admin;
use Skillberto\SonataPageMenuBundle\Entity\Menu;
use Skillberto\SonataPageMenuBundle\Site\OptionalSiteInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\PageBundle\Model\PageManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;

class MenuAdmin extends Admin
{
    protected
        $managerRegistry,
        $pageManagerInterface,
        $optionalSiteInterface,
        $formAttribute = array(),
        $pageInstance,
        $siteInstance,
        $last_positions = array()
    ;

    /**
     * Set ContainerAware instance into this class, and construct the parent
     */
    public function __construct($code, $class, $baseControllerName, PageManagerInterface $pageManagerInterface, OptionalSiteInterface $optionalSiteInterface)
    {
        $this->pageManagerInterface  = $pageManagerInterface;
        $this->optionalSiteInterface = $optionalSiteInterface;

        parent::__construct($code, $class, $baseControllerName);
    }

    /**
     * @return array
     */
    public function getPersistentParameters()
    {
        return array(
            'provider'  => $this->getRequest()->get('provider'),
            'site'      => $this->getRequest()->get('site'),
        );
    }

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
     * @param   string $context
     *
     * @return \Sonata\AdminBundle\Datagrid\ProxyQueryInterface
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere(
            $query->expr()->eq($query->getRootAlias() . '.site', ':my_param')
        );

        $query->addOrderBy($query->getRootAlias() .'.root', 'ASC');
        $query->addOrderBy($query->getRootAlias() .'.lft', 'ASC');
        $query->setParameter('my_param', $this->getCurrentSite());

        return $query;
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('parent')
            ->addConstraint(
                new Assert\NotEqualTo(
                    array('value' => $object)
                )
            )
            ->end();
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
        $collection->add('activate', $this->getRouterIdParameter().'/activate');
    }

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            #->add('position')
            ->add('page')
            ->add('parent')
            ->add('active')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        if ($this->getSubject() && $this->getSubject()->getId()) {
            $this->initializeEditForm();
        } else {
            $this->initializeCreateForm();
        }

        $formMapper
             ->add('name', 'text')
             ->add('page', 'sonata_page_selector', array(
                        'site'          => $this->siteInstance,
                        'model_manager' => $this->getModelManager(),
                        'class'         => 'Application\Sonata\PageBundle\Entity\Page',
                        'required'      => true
             ), array(
                        'admin_code' => 'sonata.page.admin.page',
                        'link_parameters' => array(
                            'siteId' => $this->getSubject() ? $this->getSubject()->getSite()->getId() : null
                        )
                    ))
             ->add('parent','sonata_type_model', array('required' => false))
             ->add('active', 'checkbox', array('required' => false, 'attr' => $this->formAttribute))
             ->add('clickable', 'checkbox', array('required' => false, 'attr' => $this->formAttribute))
            ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('page')
            ->add('parent')
            #->add('position')
            ->add('active')
            ->add('clickable')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit'      => array(),
                    'delete'    => array(),
                    'activate'  => array('template' => 'SkillbertoAdminBundle:Admin:list__action_activate.html.twig'),
                    'move'      => array('template' => 'SkillbertoSonataPageMenuBundle:Admin:list__action_sort.html.twig')
                    )
                )
            )
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('page')
            ->add('parent')
        ;
    }

    protected function initializeEditForm()
    {
        $page = $this->getSubject()->getPage();
        $site = $this->getSubject()->getSite();

        $this->formAttribute = array();
        $this->pageInstance  = $page;
        $this->siteInstance  = $site;
    }

    protected function initializeCreateForm()
    {
        $this->formAttribute = array('checked' => 'checked');
        $this->pageInstance  = null;
        $this->siteInstance  = $this->getCurrentSite();
    }

    /**
     * @return array
     */
    protected function getAllPages()
    {
        $currentSite = $this->getCurrentSite();

        if ($currentSite) {
            $pages = $this->pageManagerInterface->loadPages($currentSite);
        } else {
            $pages = array();
        }

        return $pages;
    }

    /**
     * @param  int $parentId
     * @return int
     */
    protected function getLastPosition($parentId)
    {
    }

    protected function getLastPositions()
    {
    }

    /**
     * @return \Sonata\PageBundle\Model\Site
     */
    protected function getCurrentSite()
    {
        return $this->optionalSiteInterface->getChosenSite();
    }
}
