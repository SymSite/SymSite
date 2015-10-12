<?php

namespace Symsite\Bundle\BlogBundle\Entity;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function qbFindWithCountArticle()
    {
        return $this->createQueryBuilder('c')
          ->select('c as category', 'count(a) as count_article')
          ->leftjoin('c.articles', 'a')
          ->groupBy('c');
    }

    /**
     * @param array $ids
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function qbDeleteByIds(array $ids)
    {
        return $this->createQueryBuilder('c')
          ->delete()
          ->where('c.id IN(:ids)')
          ->setParameter('ids', $ids);
    }
}