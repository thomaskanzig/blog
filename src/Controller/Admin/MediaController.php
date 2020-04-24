<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Repository\MediaRepository;
use App\Repository\FolderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

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

    /**
     * @Route("/admin/media/{id}/edit", name="admin_media_edit")
     *
     * @param Media $media
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param TranslatorInterface $translator
     *
     * @return Response
     */
    public function edit(
        Media $media,
        EntityManagerInterface $em,
        Request $request,
        TranslatorInterface $translator
    ) {
        // Verify if exist media data from locale, if not then will created.
        // TODO: Create an repository function script for that verification.

        // Create the form based on the FormType we need.
        $mediaForm = $this->createForm(MediaType::class, $media);

        // Ask the form to handle the current request.
        $mediaForm->handleRequest($request);

        if ($mediaForm->isSubmitted() && $mediaForm->isValid()) {

            // To save.
            $em->persist($media);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', $translator->trans('admin.posts.form.post_saved'));

            // Redirect to another page.
            return $this->redirectToRoute('admin_post_index', [
                'id' => $media->getId()
            ]);
        }

        return $this->render('admin/media/edit.html.twig', [
            'postForm' => $mediaForm->createView(),
            'id' => $media->getId(),
        ]);
    }

}
