<?php

namespace App\Repository;

use App\Entity\ModuleContextSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModuleContextSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleContextSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleContextSetting[]    findAll()
 * @method ModuleContextSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleContextSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleContextSetting::class);
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
