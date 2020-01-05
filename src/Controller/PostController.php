<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{

    /**
     * @Route("/", name="post_index")
     */
    public function index(PostRepository $repository, Request $request, PaginatorInterface $paginator)
    {

        $q = $request->query->get('q'); /* get text search */
        $queryBuilder = $repository->getWithSearchQueryBuilder($q, [
            'active' => true
        ]);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/* page number */,
            10 /* limit per page */
        );

        // Get globals variables from twig.
        $twigGlobals = $this->get('twig')->getGlobals();

        return $this->render('post/index.html.twig', [
            'posts' => $pagination,
            'metaTitle' => 'Blog - '.$twigGlobals['name_site'],
        ]);
    }


    /**
     * @Route("/{slug}", name="post_detail")
     */
    public function detail($slug)
    {
        /** @var Post $post */
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findOneBy(['slug' => $slug]);

        if (!$post) {
            throw $this->createNotFoundException(
                'Post not found'
            );
        }

        return $this->render($post->getTemplate()->getView(), [
            'post' => $post,
            'metaTitle' => $post->getTitle(),
            'metaDescription' => $post->getDescription(),
            'metaImage' => $post->getUrlPhoto(),
        ]);
    }
}
