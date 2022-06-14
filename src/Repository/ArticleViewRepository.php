<?php

namespace App\Repository;

use App\Entity\ArticleView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleView|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleView|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleView[]    findAll()
 * @method ArticleView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleView::class);
    }

    // /**
    //  * @return ArticleView[] Returns an array of ArticleView objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ArticleView
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
