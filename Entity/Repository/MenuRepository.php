<?php


namespace Skillberto\SonataPageMenuBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Sortable\SortableListener;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Skillberto\SonataPageMenuBundle\Exception\InvalidArgumentException;

class MenuRepository extends NestedTreeRepository
{
    public function getMaxPositionByParentId($parent = null)
    {

        $qb = $this->createQueryBuilder('e');
        $qb->select('MAX(e.position) as max_position');

        if ($parent != null) {
            $qb->where('e.parent = :parent');
            $qb->setParameter('parent', $parent);
        } else {
            $qb->where('e.parent IS NULL');
        }

        $res = $qb->getQuery()->getResult();

        return $res[0]['max_position'];
    }

    public function getParentChildNumber()
    {
        $qb = $this->createQueryBuilder('m')
            ->select('IDENTITY(m.parent) as parent')
            ->addSelect('COUNT(m) as size')
            ->groupBy('m.parent')
            ->addOrderBy('m.root', 'ASC')
            ->addOrderBy('m.lft', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }
}