<?php

namespace App\Repository;

use App\Entity\ProfessionalLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfessionalLanguage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfessionalLanguage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfessionalLanguage[]    findAll()
 * @method ProfessionalLanguage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfessionalLanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfessionalLanguage::class);
    }

    // /**
    //  * @return ProfessionalLanguage[] Returns an array of ProfessionalLanguage objects
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
    public function findOneBySomeField($value): ?ProfessionalLanguage
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
