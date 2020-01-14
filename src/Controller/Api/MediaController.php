<?php

namespace App\Controller\Api;

use App\Entity\Media;
use App\Entity\MediaType;
use App\Entity\Folder;
use App\Service\UploaderHelper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Liip\ImagineBundle\Service\FilterService;

class MediaController extends AbstractController
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
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * MediaController constructor.
     *
     * @param UploaderHelper $uploaderHelper
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator,
     */
    public function __construct(
        UploaderHelper $uploaderHelper,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator
    ) {
        $this->uploaderHelper = $uploaderHelper;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/api/media/upload", name="api_media_upload", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws InvalidCsrfTokenException if the provided argument token is invalid.
     */
    public function uploadAction(Request $request): JsonResponse
    {
        $success = false;
        $errors = [];
        $message = 'Upload failed.';

        $file = $request->files->get('file');
        $submittedToken = $request->request->get('token');

        if (!$this->isCsrfTokenValid('media', $submittedToken)) {
            throw new InvalidCsrfTokenException();
        }

        if ($file instanceof UploadedFile) {

            /** @var String[] $uploadMedia */
            $uploadMedia = $this->uploaderHelper->uploadMedia($file);
            $message = 'Upload successfully';
            $success = true;

            /** @var Media $media */
            $media = new Media();
            $media->setTitle($uploadMedia['name']);
            $media->setFile($uploadMedia['file']);
            $media->setExternal(false);
            $media->setCreated(new \DateTime());

            /** @var MediaType $type */
            $type = $this->entityManager
                ->getRepository(MediaType::class)
                ->findOneByMimeType($file->getClientMimeType());
            $media->setType($type);

            /** @var Folder $folder */
            $folder = $this->entityManager
                ->getRepository(Folder::class)
                ->find($request->request->get('folderId'));
            $media->setFolder($folder);

            $this->entityManager->persist($media);
            $this->entityManager->flush();
        }

        $response = new JsonResponse();
        $response->setData([
            'success' => $success,
            'message' => $message,
            'media' => $uploadMedia['file'],
            'errors' => $errors
        ]);

        return $response;
    }

    /**
     * @Route("/api/media/delete", name="api_media_delete", methods={"DELETE"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws InvalidCsrfTokenException if the provided argument token is invalid.
     */
    public function deleteAction(Request $request): JsonResponse
    {
        $success = false;
        $message = 'Delete failed.';

        $mediaId = $request->request->get('mediaId');
        $submittedToken = $request->request->get('token');

        if (!$this->isCsrfTokenValid('media', $submittedToken)) {
            throw new InvalidCsrfTokenException();
        }

        if ($mediaId) {
            /** @var Media $media */
            $media = $this->entityManager
                ->getRepository(Media::class)
                ->find($mediaId);

            $this->entityManager->remove($media);
            $this->entityManager->flush();

            // When not external then will delete this file.
            if(!$media->getExternal()){
                $this->uploaderHelper->deleteFile($media->getFile(), false);
            }

            $message = 'Delete successfully';
            $success = true;
        }

        $response = new JsonResponse();
        $response->setData([
            'success' => $success,
            'message' => $message
        ]);

        return $response;
    }

    /**
     * @Route("/api/media/list", name="api_media_list", methods={"GET"})
     *
     * @param Request $request
     * @param FilterService $filterService
     *
     * @return JsonResponse
     *
     * @throws InvalidCsrfTokenException if the provided argument token is invalid.
     */
    public function listAction(Request $request, FilterService $filterService): JsonResponse
    {
        $message = 'List successfully';
        $success = true;
        $submittedToken = $request->query->get('token');

        if (!$this->isCsrfTokenValid('media', $submittedToken)) {
            throw new InvalidCsrfTokenException();
        }

        /** @var Media[] $queryBuilder */
        $queryBuilder = $this->entityManager
            ->getRepository(Media::class)
            ->findAll();

        /** @var PaginatorInterface $pagination */
        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/* page number */,
            20 /* limit per page */
        );

        /** @var Media[] $items */
        $items = $pagination->getItems();
        foreach ($items as $key => $item) {
            $resourcePath = $filterService->getUrlOfFilteredImage($item->getFile(), '350x350');

            $item->setFile($resourcePath);
            $items[$key] = $item;
        }

        // More about serialize, visit: https://symfony.com/doc/current/components/serializer.html
        /** @var Serializer $serializer */
        $serializer = new Serializer([new ObjectNormalizer()]);
        $jsonData = $serializer->normalize($items, 'json');

        $response = new JsonResponse();
        $response->setData([
            'success' => $success,
            'message' => $message,
            'files' => $jsonData,
            'pagination' => $pagination->getPaginationData()
        ]);

        return $response;
    }
}
