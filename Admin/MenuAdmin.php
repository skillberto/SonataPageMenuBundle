<?php


namespace Skillberto\SonataPageMenuBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\PageBundle\Model\PageManagerInterface;

class MenuAdmin extends BaseMenuAdmin
{
    protected $baseRouteName = "skillberto_admin_menu";

    protected $baseRoutePattern = "menu";

    /**
     * {@inheritdoc}
     */
    public function getPersistentParameters()
    {
        return array(
            'provider' => $this->getRequest()->get('provider'),
            'site'     => $this->getRequest()->get('site'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createBaseQuery($context);

        $query->andWhere(
            $query->expr()->eq($query->getRootAlias() . '.lvl', ':level')
        );

        $query->setParameter('level', 0);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('activate', $this->getRouterIdParameter() . '/activate');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, array('route' => array('name' => 'skillberto_admin_menu_child_list')))
            ->add('active')
            ->add('_action', 'actions', array(
                    'actions' => array(
                        //'list' => array('template' => 'SkillbertoAdminBundle:Admin:list__action_list.html.twig'),
                        'edit' => array(),
                        'delete' => array(),
                        'activate' => array('template' => 'SkillbertoAdminBundle:Admin:list__action_activate.html.twig'),
                    )
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $context = $this->getPersistentParameter('context');

        $formMapper
            ->with($this->trans('form_menu.group_main_label'), array('class' => 'col-md-6'))->end()
            ->with($this->trans('form_menu.group_attribute_label'), array('class' => 'col-md-6'))->end()
        ;

        $formMapper
            ->with($this->trans('form_menu.group_main_label'))
                ->add('name', 'text')
                ->add('active', 'checkbox', array('required' => false, 'attr' => $this->formAttribute))
            ->end()
            ->with($this->trans('form_menu.group_attribute_label'))
                ->add('attributes', 'text')
            ->end()
        ;
    }

    protected function initializeEditForm()
    {
        $this->formAttribute = array();
    }

    protected function initializeCreateForm()
    {
        $this->formAttribute = array('checked' => 'checked');
    }
}