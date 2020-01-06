<?php

namespace App\Controller\Admin;

use App\Entity\MediaPostRel;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Service\UploaderHelper;
use Cocur\Slugify\Slugify;

class PostController extends AbstractController
{

    /**
     * @Route("/admin/posts", name="admin_post_index")
     */
    public function index(PostRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $q = $request->query->get('q'); /* get text search */
        $queryBuilder = $repository->getWithSearchQueryBuilder($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/* page number */,
            10 /* limit per page */
        );

        return $this->render('admin/post/index.html.twig', [
            'posts' => $pagination,
        ]);
    }

    /**
     * @Route("/admin/post/add", name="admin_post_add")
     */
    public function add(EntityManagerInterface $em, Request $request, UploaderHelper $uploaderHelper)
    {
        // Create the form based on the FormType we need.
        $postForm = $this->createForm(PostType::class);

        // Ask the form to handle the current request.
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            // Get data of form.
            $post = $postForm->getData();

            // Send an image file an store in /public.
            $uploadedFile = $postForm['imageFile']->getData();
            if ($uploadedFile) {
                $newFile = $uploaderHelper->uploadMedia($uploadedFile);
                $post->setUrlPhoto($newFile['file']);
            }

            // Set some others information of post.
            $slugify = new Slugify();
            $post->setSlug($slugify->slugify($post->getTitle()));

            // When not choise an user, then set the user logged.
            if(!$post->getUser()){
                $post->setUser($this->getUser());
            }

            $post->setCreated(new \DateTime());

            // To save.
            $em->persist($post);
            $em->flush();

            // Save images.
            $medias = json_decode($postForm['images']->getData(), true);
            $em->getRepository(MediaPostRel::class)->saveAll($medias, $post->getId());

            // Set an message after save.
            $this->addFlash('success', 'Post Created!');

            // Redirect to another page.
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/post/add.html.twig', [
            'postForm' => $postForm->createView()
        ]);
    }


    /**
     * @Route("/admin/post/{id}/edit", name="admin_post_edit")
     */
    public function edit(Post $post, EntityManagerInterface $em, Request $request, UploaderHelper $uploaderHelper)
    {

        // Create the form based on the FormType we need.
        $postForm = $this->createForm(PostType::class, $post);

        // Ask the form to handle the current request.
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {

            // Send an image file an store in /public.
            $uploadedFile = $postForm['imageFile']->getData();
            if ($uploadedFile) {
                $newFile = $uploaderHelper->uploadMedia($uploadedFile);
                $post->setUrlPhoto($newFile['file']);
            }

            // To save.
            $em->persist($post);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', 'Post Updated!');

            // Redirect to another page.
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/post/edit.html.twig', [
            'postForm' => $postForm->createView(),
            'id' => $post->getId()
        ]);
    }

    /**
     * @Route("/admin/post/{id}/delete", name="admin_post_delete")
     */
    public function delete(Post $post, EntityManagerInterface $em)
    {

        if ($post instanceof Post) {

            // Set an date to delete.
            $post->setDeleted(new \DateTime());

            // To save.
            $em->persist($post);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', 'Post Deleted!');
        }

        // Redirect to list.
        return $this->redirectToRoute('admin_post_index');
    }

}
