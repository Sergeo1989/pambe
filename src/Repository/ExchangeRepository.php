<?php

namespace App\Repository;

use App\Entity\Exchange;
use App\Entity\Invite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Exchange|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exchange|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exchange[]    findAll()
 * @method Exchange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exchange::class);
    }

    /**
     * Retourne les messages non lus de l'invité par l'admin
     *
     * @param Invite $invite
     * @return Exchange[]
     */
    public function getExchangesByInvite($invite)
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.admin', 'a')
            ->where('a IS NULL AND e.is_read = 0 AND e.invite = :invite')
            ->setParameter('invite', $invite)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Retourne les messages de l'invité
     *
     * @param Invite $invite
     * @return Exchange[]
     */
    public function getExchanges($invite)
    {
        return $this->createQueryBuilder('e')
            ->where('e.invite = :invite')
            ->setParameter('invite', $invite)
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return Exchange[] Returns an array of Exchange objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Exchange
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
