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

        if(array_key_exists( 'active', $where)){
            if (true === $where['active']) {
                $qb->andWhere('p.active = 1');
            } else {
                $qb->andWhere('p.active = 0');
            }
        }

        return $qb
            ->andWhere('p.deleted IS NULL')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
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
