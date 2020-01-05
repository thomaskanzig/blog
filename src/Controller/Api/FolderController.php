<?php

namespace App\Controller\Api;

use App\Entity\Media;
use App\Entity\MediaType;
use App\Entity\Folder;
use App\Repository\PostRepository;
use App\Service\UploaderHelper;
use App\Service\MediaHelper;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MediaTypeRepository;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class FolderController extends AbstractController
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
     * MediaController constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/folder", name="api_folder_post", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws InvalidCsrfTokenException if the provided argument token is invalid.
     */
    public function postAction(Request $request): JsonResponse
    {
        $data = [
            'success' => false,
            'message' => 'Added folder failed.',
            'errors' => []
        ];

        $name = $request->request->get('name');
        $parentId = $request->request->get('parentId');
        $submittedToken = $request->request->get('token');

        if (!$this->isCsrfTokenValid('media', $submittedToken)) {
            throw new InvalidCsrfTokenException();
        }

        if ($name) {
            /** @var Folder $folder */
            $folder = new Folder();
            $folder->setName($name);

            /** @var Slugify $slugify */
            $slugify = new Slugify();
            $folder->setSlug($slugify->slugify($folder->getName()));

            if ($parentId) {
                $folder->setParentId($parentId);
            }

            $folder->setCreated(new \DateTime());

            $this->entityManager->persist($folder);
            $this->entityManager->flush();

            $data['message'] = 'Folder created successfully';
            $data['success'] = true;
            $data['folder'] = [
                'id' => $folder->getId(),
                'name' => $folder->getname(),
            ];
        }

        $response = new JsonResponse();
        $response->setData($data);

        return $response;
    }

    /**
     * @Route("/api/folder/delete", name="api_folder_delete", methods={"DELETE"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws InvalidCsrfTokenException if the provided argument token is invalid.
     */
    public function deleteAction(Request $request, MediaHelper $mediaHelper): JsonResponse
    {
        $success = false;
        $message = 'Delete failed.';

        $folderId = $request->request->get('id');
        $submittedToken = $request->request->get('token');

        if (!$this->isCsrfTokenValid('media', $submittedToken)) {
            throw new InvalidCsrfTokenException();
        }

        if (0 < $folderId) {
            /** @var Folder $folder */
            $folder = $this->entityManager
                ->getRepository(Folder::class)
                ->find($folderId);

            $this->entityManager->remove($folder);
            $this->entityManager->flush();

            // Will delete all children folders and your files.
            $mediaHelper->deleteChildrenFoldersAndFiles($folderId);

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
}
