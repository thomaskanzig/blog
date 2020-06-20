<?php

namespace App\Controller\Website;

use App\Entity\MediaData;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\MediaDataRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    public function index(PostRepository $postRepository, Request $request, PaginatorInterface $paginator, CategoryRepository $categoryRepository, string $category = null)
    {
        // Get category if exist.
        if ($category) {
            $category = $categoryRepository->findOneBy(['slug' => $category]);
        }

        $q = $request->query->get('q'); /* get text search */
        $queryBuilder = $postRepository->getWithSearchQueryBuilder($q, [
            'active' => true,
            'locale' => $request->getLocale(),
            'category' => $category
        ]);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/* page number */,
            10 /* limit per page */
        );

        // Get globals variables from twig.
        $twigGlobals = $this->get('twig')->getGlobals();

        dump($pagination);

        return $this->render('post/index.html.twig', [
            'posts' => $pagination,
            'metaTitle' => 'Blog - '.$twigGlobals['name_site'],
        ]);
    }

    public function detail($slug, Request $request, MediaDataRepository $mediaDataRepository)
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

        /** @var Post[] $posts */
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findWithExcluded([
                'templateId' => $post->getTemplate()->getId(),
                'limit' => 3,
                'locale' => $request->getLocale(),
            ], $post->getId());

        /** @var MediaData[] $mediaDatas */
        $mediaDataResults = $mediaDataRepository->findAllByMediaPostRelsAndLocale(
            $post->getMediaPostRel(),
            $request->getLocale()
        );

        // Prepare data of all media in this posts.
        $mediaData = [];
        foreach($mediaDataResults as $data) {
            $mediaData[$data->getMedia()->getId()] = $data;
        }

        return $this->render($post->getTemplate()->getView(), [
            'post' => $post,
            'metaTitle' => $post->getTitle(),
            'metaDescription' => $post->getDescription(),
            'metaImage' => $post->getUrlPhoto(),
            'posts' => $posts,
            'mediaData' => $mediaData
        ]);
    }
}
