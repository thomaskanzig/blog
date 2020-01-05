<?php

namespace App\Repository;

use App\Entity\MediaType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MediaType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaType[]    findAll()
 * @method MediaType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaTypeRepository extends ServiceEntityRepository
{
    private $mimeTypeToSimply = [
        'image/jpeg' => 'image',
        'image/jpg' => 'image',
        'image/png' => 'image',
        'image/gif' => 'image',
        'application/pdf' => 'pdf',
        ];

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MediaType::class);
    }

    public function findOneByMimeType($mimeType): ?MediaType
    {
        return $this->createQueryBuilder('mt')
            ->andWhere('mt.slug = :val')
            ->setParameter('val', $this->mimeTypeToSimply[$mimeType] )
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return MediaType[] Returns an array of MediaType objects
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
    public function findOneBySomeField($value): ?MediaType
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
