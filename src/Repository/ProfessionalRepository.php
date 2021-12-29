<?php

namespace App\Repository;

use App\Entity\Professional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Professional|null find($id, $lockMode = null, $lockVersion = null)
 * @method Professional|null findOneBy(array $criteria, array $orderBy = null)
 * @method Professional[]    findAll()
 * @method Professional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfessionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Professional::class);
    }

    public function searchByName($words = null, $category = null, $address = null){
        $query = $this->createQueryBuilder('p');
        $query->where('p.status = 1');
        if($words != null || $address != null) $query->leftJoin('p.user', 'u');
        if($words != null){
            $query->andWhere('u.firstname LIKE :val1 OR u.lastname LIKE :val1')
                ->setParameter('val1', '%'.$words.'%');
        }
        if($category != null){
            $query->andWhere(':category MEMBER OF p.category_professionals')
                ->setParameter('category', $category);
        }
        if($address != null){
            $query->andWhere('u.address LIKE :val2')
                ->setParameter('val2', '%'.$address.'%');
        }

        return $query->getQuery()->getResult();
    }

    public function searchByService($words = null, $category = null, $address = null){
        $query = $this->createQueryBuilder('p');
        $query->where('p.status = 1');
        if($words != null){
            $query->leftJoin('p.services', 's')
                ->andWhere('s.title LIKE :val1')
                ->setParameter('val1', '%'.$words.'%');
        }
        if($category != null){
            $query->andWhere(':category MEMBER OF p.category_professionals')
                ->setParameter('category', $category);
        }
        if($address != null){
            $query->leftJoin('p.user', 'u')
                ->andWhere('u.address LIKE :val2')
                ->setParameter('val2', '%'.$address.'%');
        }

        return $query->getQuery()->getResult();
    }

    public function searchByQualification($words = null, $category = null, $address = null){
        $query = $this->createQueryBuilder('p');
        $query->where('p.status = 1');
        if($words != null){
            $query->leftJoin('p.qualifications', 'q')
                ->andWhere('q.title LIKE :val1')
                ->setParameter('val1', '%'.$words.'%');
        }
        if($category != null){
            $query->andWhere(':category MEMBER OF p.category_professionals')
                ->setParameter('category', $category);
        }
        if($address != null){
            $query->leftJoin('p.user', 'u')
                ->andWhere('u.address LIKE :val2')
                ->setParameter('val2', '%'.$address.'%');
        }

        return $query->getQuery()->getResult();
    }

    public function searchByDescription($words = null, $category = null, $address = null){
        $query = $this->createQueryBuilder('p');
        $query->where('p.status = 1');
        if($words != null){
            $query->andWhere('p.description LIKE :val1')
                ->setParameter('val1', '%'.$words.'%');
        }
        if($category != null){
            $query->andWhere(':category MEMBER OF p.category_professionals')
                ->setParameter('category', $category);
        }
        if($address != null){
            $query->leftJoin('p.user', 'u')
                ->andWhere('u.address LIKE :val2')
                ->setParameter('val2', '%'.$address.'%');
        }

        return $query->getQuery()->getResult();
    }


    // /**
    //  * @return Professional[] Returns an array of Professional objects
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
    public function findOneBySomeField($value): ?Professional
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
