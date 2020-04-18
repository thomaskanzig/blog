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

        return $qb
            ->andWhere('p.deleted IS NULL')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ;
    }

    /**
     * Finds posts for display more content.
     *
     * @param mixed[] $criteria
     *
     * @return Post[] List posts.
     */
    public function getSeeMore(?array $criteria)
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.user', 'u')
            // For select specific fields its better use select()
            // Read for more: https://symfonycasts.com/screencast/doctrine-queries/select-specific-fields
            ->select(
                'p.id, 
                        p.title, 
                        p.slug, 
                        p.url_photo image, 
                        p.created,  
                        u.fullname userName,
                        u.url_avatar userAvatar'
            )
        ;

        if (array_key_exists( 'exceptId', $criteria)) {
            $qb->andWhere('p.id != :exceptId')
               ->setParameter('exceptId', $criteria['exceptId']);
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

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
