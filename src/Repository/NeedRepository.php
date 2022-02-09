<?php

namespace App\Repository;

use App\Entity\Need;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Need|null find($id, $lockMode = null, $lockVersion = null)
 * @method Need|null findOneBy(array $criteria, array $orderBy = null)
 * @method Need[]    findAll()
 * @method Need[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NeedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Need::class);
    }

    public function getNeedsBetween2Dates($date1, $date2)
    {
        return $this->createQueryBuilder('n')
            ->where('n.day >= :date1 AND n.day <= :date2')
            ->setParameter('date1', $date1)
            ->setParameter('date2', $date2)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Need[] Returns an array of Need objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Need
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
