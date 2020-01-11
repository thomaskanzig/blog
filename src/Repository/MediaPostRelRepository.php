<?php

namespace App\Repository;

use App\Entity\Media;
use App\Entity\MediaPostRel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManagerInterface;

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

    public function saveAll(int $postId, $medias = [])
    {
        // Delete all relations.
        $this->createQueryBuilder('mpr')
            ->delete()
            ->andWhere('mpr.post_id = :post_id')
            ->setParameter('post_id', $postId);

        if ($medias && 0 < $postId) {
            $em = $this->getEntityManager();

            foreach($medias as $mediaId) {
                $mediaPostRel = new MediaPostRel();
                $mediaPostRel->setPostId($postId);
                $mediaPostRel->setMediaId($mediaId);
                $mediaPostRel->setCreated(new \DateTime());

                $em->persist($mediaPostRel);
            }

            $em->flush();
        }
    }

    public function findAllMediasByPostId($postId)
    {
        return $this->createQueryBuilder('mpr')
            ->innerJoin('mpr.media', 'm')
            ->andWhere('mpr.post_id = :post_id')
            ->setParameter('post_id', $postId)
            ->addSelect('m')
            ->getQuery()
            ->getResult()
            ;
    }
}
