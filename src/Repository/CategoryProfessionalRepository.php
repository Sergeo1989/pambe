<?php

namespace App\Repository;

use App\Entity\CategoryProfessional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryProfessional|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryProfessional|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryProfessional[]    findAll()
 * @method CategoryProfessional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryProfessionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryProfessional::class);
    }

    // /**
    //  * @return CategoryProfessional[] Returns an array of CategoryProfessional objects
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
    public function findOneBySomeField($value): ?CategoryProfessional
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
