<?php

namespace App\Repository;

use App\Entity\ProfessionalView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ViewCounter|null find($id, $lockMode = null, $lockVersion = null)
 * @method ViewCounter|null findOneBy(array $criteria, array $orderBy = null)
 * @method ViewCounter[]    findAll()
 * @method ViewCounter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfessionalViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfessionalView::class);
    }

    public function getVisitorsBetween2Dates($date1, $date2)
    {
        return $this->createQueryBuilder('v')
            ->where('v.day >= :date1 AND v.day <= :date2')
            ->setParameter('date1', $date1)
            ->setParameter('date2', $date2)
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return ViewCounter[] Returns an array of ViewCounter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ViewCounter
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
