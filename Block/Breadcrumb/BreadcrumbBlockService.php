<?php
/**
 * Created by PhpStorm.
 * User: pentalab_2
 * Date: 2014.11.13.
 * Time: 19:59
 */

namespace Skillberto\SonataPageMenuBundle\Block\Breadcrumb;

use Knp\Menu\Provider\MenuProviderInterface;
use Knp\Menu\FactoryInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\SeoBundle\Block\Breadcrumb\BaseBreadcrumbMenuBlockService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BreadcrumbBlockService extends BaseBreadcrumbMenuBlockService
{
    protected
        $request_stack;

    public function __construct(
        $context,
        $name,
        $menuEntity,
        EngineInterface $templating,
        MenuProviderInterface $menuProvider,
        FactoryInterface $factory,
        RequestStack $requestStack
    ) {
        $this->request_stack = $requestStack;

        parent::__construct($context, $name, $templating, $menuProvider, $factory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "skillberto.sonatamenu.block.breadcrumb";
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        parent::setDefaultSettings($resolver);

        $resolver->setDefaults(array(
            'menu_template'         => 'SkillbertoSonataPageMenuBundle:Block:breadcrumb.html.twig',
            'include_homepage_link' => true,
        ));
    }

    public function getMenu(BlockContextInterface $blockContext)
    {
        $menu = $this->getRootMenu($blockContext);

        return $menu;
    }
}