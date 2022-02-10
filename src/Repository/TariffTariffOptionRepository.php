<?php

namespace App\Repository;

use App\Entity\TariffTariffOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TariffTariffOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method TariffTariffOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method TariffTariffOption[]    findAll()
 * @method TariffTariffOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TariffTariffOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TariffTariffOption::class);
    }

    // /**
    //  * @return TariffTariffOption[] Returns an array of TariffTariffOption objects
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
    public function findOneBySomeField($value): ?TariffTariffOption
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
