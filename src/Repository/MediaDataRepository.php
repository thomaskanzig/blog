<?php

namespace App\Repository;

use App\Entity\MediaData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MediaData|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaData|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaData[]    findAll()
 * @method MediaData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaData::class);
    }

    // /**
    //  * @return MediaData[] Returns an array of MediaData objects
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
    public function findOneBySomeField($value): ?MediaData
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
