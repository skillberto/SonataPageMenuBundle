<?php

namespace Skillberto\SonataPageMenuBundle\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Util\ClassUtils;
use Skillberto\SonataPageMenuBundle\Util\PositionHandler;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\PageBundle\Model\PageManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ChildMenuAdmin extends BaseMenuAdmin
{
    protected $positionHandler;

    protected $baseRouteName = "skillberto_admin_menu_child";

    protected $baseRoutePattern = "menu/{id}/child";

    protected $parentAssociationMapping = 'menu';

    public function isChild()
    {
        return true;
    }

    public function getParent()
    {
        if (!$this->parent) {
            $this->parent = $this->getConfigurationPool()->getContainer()->get('skillberto.sonata_page_menu.admin.category');
        }

        return $this->parent;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        return parent::createBaseQuery($context);
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
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter() . '/move/{position}');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('page')
            ->add('parent')
            ->add('active');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text')
            ->add('page', 'sonata_page_selector', array(
                'site' => $this->siteInstance,
                'model_manager' => $this->getModelManager(),
                'class' => 'Application\Sonata\PageBundle\Entity\Page',
                'required' => true
            ), array(
                'admin_code' => 'sonata.page.admin.page',
                'link_parameters' => array(
                    'siteId' => $this->getSubject() && $this->getSubject()->getSite() ? $this->getSubject()->getSite()->getId() : $this->getRequest()->get('site')
                )
            ))
            ->add('parent', 'sonata_type_model', array(
                'required' => false
            ), array(
                'admin_code' => 'skillberto.sonata_page_menu.admin.child',
                'link_parameters' => array(
                    'site' => $this->getSubject() && $this->getSubject()->getSite() ? $this->getSubject()->getSite()->getId() : $this->getRequest()->get('site')
                )
            ))
            ->add('active', 'checkbox', array('required' => false, 'attr' => $this->formAttribute))
            ->add('clickable', 'checkbox', array('required' => false, 'attr' => $this->formAttribute))
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $this->positionHandler->setLastPositions($this->getLastPositionsFromDb());

        $listMapper
            ->addIdentifier('id')
            ->add('name', 'string', array('template' => 'SkillbertoSonataPageMenuBundle:Admin:base_list_field.html.twig'))
            ->add('page')
            //->add('parent', 'sonata_type_model', array(), array('admin_code' => 'skillberto.sonata_page_menu.admin.child'))
            ->add('active')
            ->add('clickable')
            ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(),
                        'delete' => array(),
                        'activate' => array('template' => 'SkillbertoAdminBundle:Admin:list__action_activate.html.twig'),
                        'move' => array('template' => 'SkillbertoSonataPageMenuBundle:Admin:list__action_sort.html.twig')
                    )
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('page')
            ->add('parent');
    }

    /**
     * @return array
     */
    protected function getLastPositionsFromDb()
    {
        $repo = $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository($this->getClass());

        $count = array();

        foreach ($repo->getParentChildNumber() as $data) {

            if ($data['parent'] == null) {
                $data['parent'] = 0;
            }

            $count[$data['parent']] = $data['size'];
        }

        return $count;
    }

    public function delete($object)
    {
        //TODO override with tree repo 'remove'
       parent::delete($object);
    }

    protected function initializeEditForm()
    {
        $page = $this->getSubject()->getPage();
        $site = $this->getSubject()->getSite();

        $this->pageInstance = $page;
        $this->siteInstance = $site;
        $this->formAttribute = array();
    }

    protected function initializeCreateForm()
    {
        $this->formAttribute = array('checked' => 'checked');
        $this->pageInstance = null;
        $this->siteInstance = $this->getCurrentSite();
    }

    /*public function getParentAssociationMapping()
    {
        return 'menu';
    }*/
}
