<?php

namespace Skillberto\SonataPageMenuBundle\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Util\ClassUtils;
use Skillberto\SonataPageMenuBundle\Util\PositionHandler;
use Skillberto\SonataPageMenuBundle\Entity\Menu;
use Skillberto\SonataPageMenuBundle\Site\OptionalSiteInterface;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
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
        $positionHandler
    ;

    public function __construct($code, $class, $baseControllerName, PageManagerInterface $pageManagerInterface, OptionalSiteInterface $optionalSiteInterface)
    {
        $this->pageManagerInterface  = $pageManagerInterface;
        $this->optionalSiteInterface = $optionalSiteInterface;
        $this->positionHandler       = new PositionHandler();

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

    /**
     * @param  $positionHandler
     * @return $this
     */
    public function setPositionHandler($positionHandler)
    {
        $this->positionHandler = $positionHandler;

        return $this;
    }

    /**
     * @return PositionHandler
     */
    public function getPositionHandler()
    {
        return $this->positionHandler;
    }

    /**
     * @return \Sonata\PageBundle\Model\Site
     */
    public function getCurrentSite()
    {
        return $this->optionalSiteInterface->getChosenSite();
    }

    /**
     * @return mixed
     */
    public function getSites()
    {
        return $this->optionalSiteInterface->getSiteManager()->findBy(array());
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
             ->add('icon', 'text', array('required' => false))
             ->add('page', PageSelectorType::class, array(
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
             ->add('parent', 'sonata_type_model', array('required' => false))
             ->add('userRestricted', 'checkbox', array('required' => false, 'attr' => $this->formAttribute))
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
        $this->positionHandler->setLastPositions($this->getLastPositionsFromDb());

        $listMapper
            ->addIdentifier('id')
            ->add('name', 'string', array('template' => 'SkillbertoSonataPageMenuBundle:Admin:base_list_field.html.twig'))
            ->add('icon', 'string', array('template' => 'SkillbertoSonataPageMenuBundle:Admin:base_list_field.html.twig'))
            ->add('page')
            ->add('parent')
            ->add('userRestricted')
            ->add('active')
            ->add('clickable')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit'      => array(),
                    'delete'    => array(),
                    'activate'  => array('template' => 'SkillbertoSonataExtendedAdminBundle:Admin:list__action_activate.html.twig'),
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
     * @return array
     */
    protected function getLastPositionsFromDb()
    {
        $repo = $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository($this->getClass());

        $count = array();

        foreach ($repo->getParentChildNumber() as $data) {

            if ($data['parent'] == null ) {
                $data['parent'] = 0;
            }

            $count[$data['parent']] = $data['size'];
        }

        return $count;
    }
}
