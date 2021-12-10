<?php

namespace App\Repository;

use App\Entity\ProfessionalSocialUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfessionalSocialUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfessionalSocialUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfessionalSocialUrl[]    findAll()
 * @method ProfessionalSocialUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfessionalSocialUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfessionalSocialUrl::class);
    }

    // /**
    //  * @return ProfessionalSocialUrl[] Returns an array of ProfessionalSocialUrl objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProfessionalSocialUrl
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
