<?php


namespace Skillberto\SonataPageMenuBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Sortable\SortableListener;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Skillberto\SonataPageMenuBundle\Exception\InvalidArgumentException;

class MenuRepository extends NestedTreeRepository
{
    protected
        $sortableListener;

    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $listeners = $em->getEventManager()->getListeners();

        foreach ($listeners as $listener) {
            if ($listener instanceof SortableListener) {
                $this->sortableListener = $listener;

                break;
            }
        }

    }

    public function getMaxPositionByParentId($parent = null)
    {
        if ($parent == null) {
            if ($parentEntities = $this->findAllNotNull('parent')) {
                foreach ($parentEntities as $key => $entity) {
                    if ($key != 0 && $parent != $entity->getParent()->getId()) {
                        throw new InvalidArgumentException(sprintf("Can't identify which parent required, more then one found. Please add it into the argument."));
                    }

                    $parent = $entity->getParent()->getId();
                }
            }
        }

        $qb = $this->createQueryBuilder('e');
        $qb->select('MAX(e.position) as max_position');

        if ($parent != null) {
            $qb->where('e.parent = :parent');
            $qb->setParameter('parent', $parent);
        }

        $max = 0;

        foreach ($qb->getQuery()->getResult() as $result) {
            if ($result['max_position'] > $max) {
                $max = $result['max_position'];
            }
        }

        return $max;
    }

    public function findAllNotNull($field)
    {
        $qb = $this->createQueryBuilder('entity');

        $qb->where('entity.'.$field.' IS NOT NULL');

        return $qb->getQuery()->getResult();
    }
}