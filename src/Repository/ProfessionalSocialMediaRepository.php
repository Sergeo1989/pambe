<?php

namespace App\Repository;

use App\Entity\ProfessionalSocialMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfessionalSocialMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfessionalSocialMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfessionalSocialMedia[]    findAll()
 * @method ProfessionalSocialMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfessionalSocialMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfessionalSocialMedia::class);
    }

    // /**
    //  * @return ProfessionalSocialMedia[] Returns an array of ProfessionalSocialMedia objects
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
    public function findOneBySomeField($value): ?ProfessionalSocialMedia
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
