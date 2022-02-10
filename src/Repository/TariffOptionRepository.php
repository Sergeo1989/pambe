<?php

namespace App\Repository;

use App\Entity\TariffOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TariffOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method TariffOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method TariffOption[]    findAll()
 * @method TariffOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TariffOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TariffOption::class);
    }

    // /**
    //  * @return TariffOption[] Returns an array of TariffOption objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TariffOption
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
