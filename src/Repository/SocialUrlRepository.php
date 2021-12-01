<?php

namespace App\Repository;

use App\Entity\SocialUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SocialUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialUrl[]    findAll()
 * @method SocialUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocialUrl::class);
    }

    // /**
    //  * @return SocialUrl[] Returns an array of SocialUrl objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SocialUrl
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
