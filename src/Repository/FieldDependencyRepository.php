<?php

namespace App\Repository;

use App\Entity\FieldDependency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FieldDependency|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldDependency|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldDependency[]    findAll()
 * @method FieldDependency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldDependencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FieldDependency::class);
    }

    // /**
    //  * @return FieldDependency[] Returns an array of FieldDependency objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FieldDependency
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
