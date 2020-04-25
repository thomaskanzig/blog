<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Entity\MediaData;
use App\Entity\MediaPostRel;
use App\Entity\Post;
use App\Form\MediaDataType;
use App\Repository\MediaRepository;
use App\Repository\FolderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class MediaDataController extends AbstractController
{

    /**
     * @Route("/admin/media-data/{id}/edit", name="admin_media_data_edit")
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
        /* @var MediaData|null $mediaData */
        $mediaData = $em->getRepository(MediaData::class)
            ->findByMediaAndLocale(
                $media,
                $request->getLocale()
            );

        // If dont find correctly media data, then will create.
        if (!$mediaData) {
            $mediaData = new MediaData();
            $mediaData->setMedia($media);
            $mediaData->setLocale($request->getLocale());

            $em->persist($mediaData);
            $em->flush();
        }

        // Create the form based on the FormType we need.
        $mediaDataForm = $this->createForm(MediaDataType::class, $mediaData);

        // Ask the form to handle the current request.
        $mediaDataForm->handleRequest($request);

        if ($mediaDataForm->isSubmitted() && $mediaDataForm->isValid()) {
            // To save.
            $em->persist($media);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', $translator->trans('admin.data_medias.form.data_saved'));

            // Redirect to another page.
            return $this->redirectToRoute('admin_media_data_edit', [
                'id' => $media->getId()
            ]);
        }

        return $this->render('admin/media_data/edit.html.twig', [
            'mediaDataForm' => $mediaDataForm->createView(),
            'id' => $media->getId()
        ]);
    }
}
