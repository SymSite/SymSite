<?php

namespace Symsite\Bundle\BlogBundle\Entity;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends \Doctrine\ORM\EntityRepository
{
    public function qbFindWithCountArticle()
    {
        return $this->createQueryBuilder('t')
          ->select('t as tag', 'count(a) as count_article')
          ->leftjoin('t.articles', 'a')
          ->groupBy('t');
    }

    /**
     * @param array $ids
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function qbDeleteByIds(array $ids)
    {
        return $this->createQueryBuilder('t')
          ->delete()
          ->where('t.id IN(:ids)')
          ->setParameter('ids', $ids);
    }
}
