<?php

namespace App\Repository;

use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Skill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Skill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Skill[]    findAll()
 * @method Skill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Skill::class);
    }

    /**
     * @return Skill[] Returns an array of Skills
     */
    public function findAllByTerm($search, $n = 8)
    {
        return $this->createQueryBuilder('s')
            ->select(array('s.name', 's.id'))
            ->andWhere('s.name LIKE :nval')
            ->setParameter('nval', '%'.$search.'%')
            ->setMaxResults($n)
            ->getQuery()
            ->getResult()
        ;
    }
}
