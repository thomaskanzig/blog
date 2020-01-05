<?php

namespace App\Repository;

use App\Entity\Folder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Folder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Folder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Folder[]    findAll()
 * @method Folder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FolderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Folder::class);
    }

    /**
     * Return all active folders from an parent id.
     *
     * @param integer $parendId
     * @return Folder[] Returns an array of Media objects
     */
    public function findByParentId(int $parendId = null)
    {
        $queryBuilder = $this->createQueryBuilder('f');

        if ($parendId) {
            $queryBuilder->andWhere('f.parent_id = :id')
                ->setParameter('id', $parendId);
        } else {
            $queryBuilder->andWhere('f.parent_id IS NULL');
        }

        return $queryBuilder
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Return all subfolders.
     *
     * @param integer $folderId
     * @return String[]|null
     */

    public function findParentFolders($folderId)
    {
        if(!$folderId){
            return false;
        }

        $folders = [];

        do {
            $folder = $this->find($folderId);

            $folders[] = $folder;
            $folderId = $folder->getParentId();

        } while (0 < $folderId);

        return array_reverse($folders);
    }

    /*
    public function findOneBySomeField($value): ?Folder
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
