<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /*
     * List all posts.
     */
    public function getWithSearchQueryBuilder(?string $term, array $where = [])
    {
        $qb = $this->createQueryBuilder('p')
        // p.user refers to the "user" property on post.
        ->innerJoin('p.user', 'u')
        // selects all the user data to avoid the query
        ->addSelect('u')
        ;

        if ($term) {
            $qb->andWhere('p.title LIKE :term')
               ->setParameter('term', '%' . $term . '%')
            ;
        }

        if(array_key_exists( 'active', $where)) {
            if (true === $where['active']) {
                $qb->andWhere('p.active = 1');
            } else {
                $qb->andWhere('p.active = 0');
            }
        }

        if (array_key_exists( 'locale', $where)) {
            $qb->andWhere('p.locale = :locale')
               ->setParameter('locale', $where['locale']);
        }

        if (array_key_exists( 'category', $where) && $where['category']) {
            $qb->innerJoin('p.categories', 'c')
                ->andWhere(':category MEMBER OF p.categories')
                ->setParameter('category', $where['category']);

        }

        return $qb
            ->andWhere('p.deleted IS NULL')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ;
    }

    /**
     * Finds posts with exclude some posts.
     *
     * @param mixed[] $criteria
     * @param int[] $excludedIds
     *
     * @return Post[] List posts.
     */
    public function findWithExcluded(?array $criteria, $excludedIds = [])
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.user', 'u')
            ->innerJoin('p.template', 't')
            // For select specific fields its better use select()
            // Read for more: https://symfonycasts.com/screencast/doctrine-queries/select-specific-fields
            ->select(
                'p.id, 
                        p.title, 
                        p.description, 
                        p.slug,
                        p.url_photo image, 
                        p.published,  
                        u.fullname userName,
                        u.url_avatar userAvatar'
            )
        ;

        if ($excludedIds && is_array($excludedIds)) {
            $qb->andWhere('p.id NOT IN (:excludedIds)')
                ->setParameter('excludedIds', $excludedIds);
        } else if($excludedIds) {
            $qb->andWhere('p.id != :exceptId')
                ->setParameter('exceptId', $excludedIds);
        }

        if (array_key_exists( 'templateId', $criteria)) {
            $qb->andWhere('t.id = :templateId')
                ->setParameter('templateId', $criteria['templateId']);
        }

        if (array_key_exists( 'locale', $criteria)) {
            $qb->andWhere('p.locale = :locale')
                ->setParameter('locale', $criteria['locale']);
        }

        // Default criteria.
        $qb->andWhere('p.url_photo IS NOT NULL')
           ->andWhere('p.active = 1')
           ->andWhere('p.deleted IS NULL');

        if (array_key_exists( 'limit', $criteria)) {
            $qb->setMaxResults($criteria['limit']);
        }

        return $qb
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
