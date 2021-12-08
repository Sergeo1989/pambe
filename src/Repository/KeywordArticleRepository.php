<?php

namespace App\Repository;

use App\Entity\KeywordArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method KeywordArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method KeywordArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method KeywordArticle[]    findAll()
 * @method KeywordArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KeywordArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KeywordArticle::class);
    }

    // /**
    //  * @return KeywordArticle[] Returns an array of KeywordArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?KeywordArticle
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
