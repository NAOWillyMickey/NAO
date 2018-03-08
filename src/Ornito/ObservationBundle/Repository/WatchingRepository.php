<?php

namespace Ornito\ObservationBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Ornito\ObservationBundle\Entity\Watching;


/**
 * WatchingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WatchingRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @param $page
     * @param $nbPerPage
     * @return Paginator
     */
    function getObs($page, $nbPerPage)
    {
        $qb = $this->createQueryBuilder('w')
            ->leftJoin('w.user', 'user')
            ->addSelect('user')
            ->leftJoin('w.image', 'img')
            ->addSelect('img')
            ->leftJoin('w.species', 'species')
            ->addSelect('species')
            ->where('w.validateStatus = :validateStatus')
            ->setParameter('validateStatus', Watching::ATTESTED)
            ->orderBy('w.date', 'DESC')
            ->getQuery()
        ;

        $qb
            ->setFirstResult(($page-1) * $nbPerPage)
            ->setMaxResults($nbPerPage)
        ;

        return new Paginator($qb, true);

    }
}

