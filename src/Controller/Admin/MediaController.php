<?php

namespace App\Controller\Admin;

use App\Repository\MediaRepository;
use App\Repository\FolderRepository;
use Symfony\Bridge\Twig\Node\RenderBlockNode;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MediaController extends AbstractController
{

    /**
     * @Route("/admin/medias", name="admin_media_index")
     *
     * @param MediaRepository $mediaRepository
     * @param FolderRepository $folderRepository
     * @param Request $request
     *
     * @return Response
     */
    public function index(MediaRepository $mediaRepository, FolderRepository $folderRepository, Request $request)
    {
        $folderId = $request->query->get('folder');
        $parentFolders = $folderRepository->findParentFolders($folderId);

        $medias = $mediaRepository->findByFolderId($folderId);
        $folders = $folderRepository->findByParentId($folderId);

        return $this->render('admin/media/index.html.twig', [
            'medias' => $medias,
            'folders' => $folders,
            'parentFolders' => $parentFolders
        ]);
    }
}
