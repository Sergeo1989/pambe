<?php

namespace App\Repository;

use App\Entity\CategoryProfessionalProfessional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryProfessionalProfessional|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryProfessionalProfessional|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryProfessionalProfessional[]    findAll()
 * @method CategoryProfessionalProfessional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryProfessionalProfessionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryProfessionalProfessional::class);
    }

    // /**
    //  * @return CategoryProfessionalProfessional[] Returns an array of CategoryProfessionalProfessional objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryProfessionalProfessional
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
