<?php

namespace App\Repository;

use App\Entity\Media;
use App\Entity\MediaType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Media::class);
    }

    /**
     * Return all active media from an parent id.
     *
     * @param integer $folderId
     * @return Media[] Returns an array of Media objects
     */
    public function findByFolderId(int $folderId = null): ?array
    {
        $queryBuilder = $this->createQueryBuilder('m');

        if ($folderId) {
            $queryBuilder->andWhere('m.folder_id = :id')
                ->setParameter('id', $folderId);
        } else {
            $queryBuilder->andWhere('m.folder_id IS NULL');
        }

        return $queryBuilder
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Return all active media from a certain type.
     *
     * @param String $type
     * @return Media[] Returns an array of Media objects
     */
    public function findAllByType(String $type = null): ?array
    {
        if ($type) {
            return $this->createQueryBuilder('m')
                ->innerJoin('m.type', 't')
                ->addSelect('t')
                ->andWhere('t.slug = :type')
                ->setParameter('type', $type)
                ->orderBy('m.id', 'DESC')
                ->getQuery()
                ->getResult()
            ;
        }

        return $this->findBy([], ['id' => 'DESC']);
    }

    /*
    public function findOneBySomeField($value): ?Media
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
