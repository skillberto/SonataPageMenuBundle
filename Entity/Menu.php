<?php

namespace Skillberto\SonataPageMenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 */
class Menu
{
    /**
     * @var integer
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $icon;
    
    /**
     * @var boolean
     */
    protected $clickable;
    
    /**
     * @var integer
     */
    protected $lft;
    
    /**
     * @var integer
     */
    protected $rgt;
    
    /**
     * @var integer
     */
    protected $root;
    
    /**
     * @var integer
     */
    protected $lvl;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $children;
    
    /**
     * @var \Application\Sonata\PageBundle\Entity\Page
     */
    protected $page;
    
    /**
     * @var \Application\Sonata\PageBundle\Entity\Site
     */
    protected $site;
    
    /**
     * @var \Skillberto\SonataPageMenuBundle\Entity\Menu
     */
    protected $parent;
    
    /**
     * @var array
     */
    protected $attribute;
    
    /**
     * @var boolean
     */
    protected $active;
    
    /**
     * @var boolean
     */
    protected $userRestricted;
    
    /**
     * @var boolean
     */
    protected $hideWhenUserConnected;
    
    /**
     * @var \DateTime
     */
    protected $createdAt;
    
    /**
     * @var \DateTime
     */
    protected $updatedAt;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
        return (string) $this->getName();
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return Menu
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set icon
     *
     * @param string $icon
     * @return Menu
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        
        return $this;
    }
    
    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }
    
    /**
     * Set clickable
     *
     * @param boolean $clickable
     * @return Menu
     */
    public function setClickable($clickable)
    {
        $this->clickable = $clickable;
        
        return $this;
    }
    
    /**
     * Get clickable
     *
     * @return boolean
     */
    public function getClickable()
    {
        return $this->clickable;
    }
    
    /**
     * Set lft
     *
     * @param integer $lft
     * @return Menu
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
        
        return $this;
    }
    
    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }
    
    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Menu
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
        
        return $this;
    }
    
    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }
    
    /**
     * Set root
     *
     * @param integer $root
     * @return Menu
     */
    public function setRoot($root)
    {
        $this->root = $root;
        
        return $this;
    }
    
    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }
    
    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Menu
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
        
        return $this;
    }
    
    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }
    
    /**
     * Add children
     *
     * @param \Skillberto\SonataPageMenuBundle\Entity\Menu $children
     * @return Menu
     */
    public function addChild(\Skillberto\SonataPageMenuBundle\Entity\Menu $children)
    {
        $this->children[] = $children;
        
        return $this;
    }
    
    /**
     * Remove children
     *
     * @param \Skillberto\SonataPageMenuBundle\Entity\Menu $children
     */
    public function removeChild(\Skillberto\SonataPageMenuBundle\Entity\Menu $children)
    {
        $this->children->removeElement($children);
    }
    
    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * Set page
     *
     * @param \Application\Sonata\PageBundle\Entity\Page $page
     * @return Menu
     */
    public function setPage(\Application\Sonata\PageBundle\Entity\Page $page = null)
    {
        $this->page = $page;
        
        return $this;
    }
    
    /**
     * Get page
     *
     * @return \Application\Sonata\PageBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }
    
    /**
     * Set site
     *
     * @param \Application\Sonata\PageBundle\Entity\Site $site
     * @return Menu
     */
    public function setSite(\Application\Sonata\PageBundle\Entity\Site $site = null)
    {
        $this->site = $site;
        
        return $this;
    }
    
    /**
     * Get site
     *
     * @return \Application\Sonata\PageBundle\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }
    
    /**
     * Set parent
     *
     * @param \Skillberto\SonataPageMenuBundle\Entity\Menu $parent
     * @return Menu
     */
    public function setParent(\Skillberto\SonataPageMenuBundle\Entity\Menu $parent = null)
    {
        $this->parent = $parent;
        
        return $this;
    }
    
    /**
     * Get parent
     *
     * @return \Skillberto\SonataPageMenuBundle\Entity\Menu
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * Set attribute
     *
     * @param array $attribute
     * @return Menu
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
        
        return $this;
    }
    
    /**
     * Get attribute
     *
     * @return array
     */
    public function getAttribute()
    {
        return $this->attribute;
    }
    
    /**
     * Set active
     *
     * @param boolean $active
     * @return Menu
     
     */
    public function setActive($active)
    {
        $this->active = $active;
        
        return $this;
    }
    
    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
    
    /**
     * Set user restricted
     *
     * @param boolean $userRestricted
     * @return Menu
     
     */
    public function setUserRestricted($userRestricted)
    {
        $this->userRestricted = $userRestricted;
        
        return $this;
    }
    
    /**
     * Get user restricted
     *
     * @return boolean
     */
    public function getUserRestricted()
    {
        return $this->userRestricted;
    }
    
    /**
     * Set hide when user connected
     *
     * @param boolean $hideWhenUserConnected
     * @return Menu
     
     */
    public function setHideWhenUserConnected($hideWhenUserConnected)
    {
        $this->hideWhenUserConnected = $hideWhenUserConnected;
        
        return $this;
    }
    
    /**
     * Get hide when user connected
     *
     * @return boolean
     */
    public function getHideWhenUserConnected()
    {
        return $this->hideWhenUserConnected;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Menu
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Menu
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    
    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    
    /**
     * @ORM\PrePersist
     */
    public function createdAt()
    {
        $this->setCreatedAt( new \DateTime("now") );
        $this->setUpdatedAt( new \DateTime("now") );
    }
    
    /**
     * @ORM\PostPersist
     */
    public function updateAt()
    {
        $this->setUpdatedAt( new \DateTime("now") );
    }
}
