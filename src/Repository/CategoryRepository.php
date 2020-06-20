<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /*
     * List all active.
     */
    public function findAllActive(array $where = [])
    {
        $qb = $this->createQueryBuilder('c');

        if (array_key_exists( 'locale', $where)) {
            $qb->andWhere('c.locale = :locale')
                ->setParameter('locale', $where['locale']);
        }

        $qb->andWhere('c.deleted IS NULL')
           ->orderBy('c.name', 'ASC');

        return $qb;
    }

    /*
     * List all with search.
     */
    public function getWithSearchQueryBuilder(?string $term, array $where = [])
    {
        $qb = $this->createQueryBuilder('c');

        if ($term) {
            $qb->andWhere('c.name LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        if (array_key_exists( 'locale', $where)) {
            $qb->andWhere('c.locale = :locale')
                ->setParameter('locale', $where['locale']);
        }

        return $qb
            ->andWhere('c.deleted IS NULL')
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ;
    }

    /*
     * TODO: Added correct query.
     * List categories used from posts.
     */
    public function findUsedPost() {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->andWhere('c.deleted IS NULL')
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ;
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
