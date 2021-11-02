<?php

namespace App\Repository;

use App\Entity\ProfessionalService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfessionalService|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfessionalService|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfessionalService[]    findAll()
 * @method ProfessionalService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfessionalServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfessionalService::class);
    }

    // /**
    //  * @return ProfessionalService[] Returns an array of ProfessionalService objects
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
    public function findOneBySomeField($value): ?ProfessionalService
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
