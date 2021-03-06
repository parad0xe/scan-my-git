<?php

namespace App\Repository;

use App\Entity\ContextModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContextModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContextModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContextModule[]    findAll()
 * @method ContextModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContextModuleRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ContextModule::class);
    }

    // /**
    //  * @return ModuleContextSetting[] Returns an array of ModuleContextSetting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModuleContextSetting
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
