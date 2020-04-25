<?php

namespace App\Repository;

use App\Entity\Media;
use App\Entity\MediaData;
use App\Entity\MediaPostRel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
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

    /**
     * Find data media from media and locale.
     *
     * @param Media $media
     * @param string $locale
     *
     * @return MediaData
     */
    public function findByMediaAndLocale(Media $media, string $locale)
    {
        $qb = $this->createQueryBuilder('md')
            ->innerJoin('md.media', 'm');

        // By media.
        $qb->andWhere('m.id = :mediaId')
            ->setParameter('mediaId', $media->getId());

        // By locale.
        $qb->andWhere('md.locale = :locale')
            ->setParameter('locale', $locale);

        return $qb->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Find all data medias from kind of media post related and locale.
     *
     * @param Collection|MediaPostRel[] $mediaPostRels
     * @param string $locale
     *
     * @return MediaData[]
     */
    public function findAllByMediaPostRelsAndLocale(?Collection $mediaPostRels, string $locale)
    {
        $qb = $this->createQueryBuilder('md')
            ->innerJoin('md.media', 'm');

        $mediaIds = [];
        foreach ($mediaPostRels as $mediaPostRel) {
            $mediaIds[] = $mediaPostRel->getMediaId();
        }

        // By all medias.
        $qb->andWhere('m.id IN (:mediaIds)')
            ->setParameter('mediaIds', $mediaIds);

        // By locale.
        $qb->andWhere('md.locale = :locale')
            ->setParameter('locale', $locale);

        return $qb->getQuery()
                  ->getResult()
            ;
    }
}
