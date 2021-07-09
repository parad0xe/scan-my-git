<?php

namespace App\Repository;

use App\Entity\ContextFieldSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContextFieldSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContextFieldSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContextFieldSetting[]    findAll()
 * @method ContextFieldSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContextFieldSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContextFieldSetting::class);
    }

    // /**
    //  * @return ContextFieldSetting[] Returns an array of ContextFieldSetting objects
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
    public function findOneBySomeField($value): ?ContextFieldSetting
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
