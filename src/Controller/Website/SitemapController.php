<?php

namespace App\Controller\Website;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(EntityManagerInterface $em)
    {
        // Static pages
        $urls[] = [
            'loc' => $this->generateUrl('post_index', [], UrlGeneratorInterface::ABSOLUTE_PATH),
            'lastmod' => '2019-06-28T11:24:19+00:00',
            'changefreq' => 'weekly',
            'priority' => '1.00'
        ];

        // Posts pages
        $queryBuilder = $em->getRepository(Post::class)
            ->getWithSearchQueryBuilder(null, [
            'active' => true
        ]);

        $posts = $queryBuilder->getResult();

        foreach($posts as $post) {
            /** @var Post $post */
            $urls[] = [
                'loc' => $this->generateUrl(
                    'post_detail', [
                                '_locale' => $post->getLocale(),
                                 'slug' => $post->getSlug()
                          ], UrlGeneratorInterface::ABSOLUTE_URL),
                'changefreq' => 'weekly',
                'priority' => '0.60'
            ];
        }

        return $this->render('sitemap.xml.twig', [
            'urls' => $urls,
        ]);
    }
}
