<?php

namespace App\Repository;

use App\Entity\MediaPostRel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MediaPostRel|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaPostRel|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaPostRel[]    findAll()
 * @method MediaPostRel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaPostRelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MediaPostRel::class);
    }

    // /**
    //  * @return MediaPostRel[] Returns an array of MediaPostRel objects
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
    public function findOneBySomeField($value): ?MediaPostRel
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
