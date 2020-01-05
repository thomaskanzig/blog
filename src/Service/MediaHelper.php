<?php
namespace App\Service;

use App\Entity\Folder;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\UploaderHelper;

/**
 * MediaHelper its an service to help for complexity task of media functionality.
 */
class MediaHelper
{
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UploaderHelper constructor.
     *
     * @param UploaderHelper $uploaderHelper
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        UploaderHelper $uploaderHelper,
        EntityManagerInterface $entityManager
    ) {
        $this->uploaderHelper = $uploaderHelper;
        $this->entityManager = $entityManager;
    }

    /**
     * Delete all children folders and medias of folder.
     *
     * @param Integer $folderParentId
     */
    public function deleteChildrenFoldersAndFiles(int $folderParentId)
    {
        $folders = $this->entityManager
            ->getRepository(Folder::class)
            ->findByParentId($folderParentId);

        foreach($folders as $folder) {

            $folderParentId = $folder->getId();

            // First delete all medias of folder.
            $this->deleteMediasOfFolder($folderParentId);

            // Delete folder.
            $this->entityManager->remove($folder);
            $this->entityManager->flush();

            // Check and delete children folders.
            $this->deleteChildrenFoldersAndFiles($folderParentId);
        }
    }

    /**
     * Delete all medias of an folder
     *
     * @param Integer $folderId
     */
    public function deleteMediasOfFolder(int $folderId)
    {
        $medias = $this->entityManager
            ->getRepository(Media::class)
            ->findByFolderId($folderId);

        foreach($medias as $media) {

            $this->entityManager->remove($media);
            $this->entityManager->flush();

            if(!$media->getExternal()){
                $this->uploaderHelper->deleteFile($media->getFile(), false);
            }
        }
    }
}