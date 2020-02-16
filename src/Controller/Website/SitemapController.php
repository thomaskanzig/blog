<?php

namespace App\Controller\Website;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use DateTimeInterface;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function index(EntityManagerInterface $em)
    {
        $urls = [];
        $locales = explode(',', $this->getParameter('app_locales'));

        // Static pages.
        $pages = [
            'post_index',
            'pages_contact',
            'pages_about_me'
        ];

        foreach($locales as $locale) {
            foreach($pages as $page) {
                $urls[] = [
                    'loc' => $this->generateUrl(
                        $page. '.' . $locale,
                        ['_locale' => $locale],
                        UrlGeneratorInterface::ABSOLUTE_PATH),
                    'changefreq' => 'weekly',
                    'priority' => '1.00'
                ];
            }
        }

        // Posts.
        foreach($locales as $locale) {
            $queryBuilder = $em->getRepository(Post::class)
                ->getWithSearchQueryBuilder(null, [
                    'active' => true,
                    'locale' => $locale
                ]);

            $posts = $queryBuilder->getResult();

            /** @var Post $post */
            foreach($posts as $post) {

                /** @var DateTimeInterface $lastMod */
                $lastMod = $post->getCreated();
                if ($post->getModified()) {
                    $lastMod = $post->getModified();
                }

                $urls[] = [
                    'loc' => $this->generateUrl(
                        'post_detail', [
                                    '_locale' => $post->getLocale(),
                                     'slug' => $post->getSlug()
                              ],
                        UrlGeneratorInterface::ABSOLUTE_URL),
                    'changefreq' => 'weekly',
                    'lastmod' => $lastMod->format('Y-m-d\TH:i:s+00:00'),
                    'priority' => '0.60'
                ];
            }
        }

        return $this->render('sitemap.xml.twig', [
            'urls' => $urls,
        ]);
    }
}
