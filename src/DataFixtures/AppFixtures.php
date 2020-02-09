<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Setting;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Template;
use App\Entity\MediaType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var String
     */
    const DEFAULT_LOCALE = 'EN';


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load all fixtures data.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadCategories($manager);
        $this->loadTemplates($manager);
        $this->loadMediaTypes($manager);
        $this->loadPosts($manager);
        $this->loadSetting($manager);

    }

    /**
     * Fixtures data of posts.
     *
     * @param ObjectManager $manager
     */
    private function loadPosts(ObjectManager $manager)
    {
        $locale = self::DEFAULT_LOCALE;

        $text = '
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis erat ex, euismod sit amet arcu at, 
        hendrerit tempor metus. Maecenas non dapibus dui. Suspendisse cursus nunc sit amet dolor ultricies dictum. 
        Quisque vel dolor feugiat, ultrices tellus quis, consectetur justo. Donec gravida, metus sed dapibus ultricies, 
        purus turpis blandit felis, vitae euismod libero orci vel quam. Mauris sit amet libero mi. Maecenas nec nisi 
        lorem. Curabitur turpis ligula, finibus ac consequat sed, vestibulum a ante.</p>

        <p>Pellentesque vel velit vel urna ultricies viverra. Pellentesque nec sapien eget turpis mollis vestibulum. 
        Ut finibus orci ut metus mollis tincidunt. Cras ut sapien maximus, mattis diam ac, auctor lectus. Nulla 
        consectetur ac sem eu lacinia. Sed tempus ipsum ac arcu consequat vulputate. Morbi ornare viverra ornare.
        Nunc iaculis sem mauris, sit amet efficitur sem rutrum et. In mi felis, accumsan in suscipit nec, commodo 
        quis enim. Mauris urna quam, pellentesque eget justo a, auctor lacinia dolor. Integer in finibus arcu. 
        Phasellus nisi felis, mattis vitae nunc ac, sollicitudin vehicula ipsum. Nulla egestas aliquet est, 
        id porta tortor. Aenean tempus non est eget dignissim.</p>
         
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis erat ex, euismod sit amet arcu at, 
        hendrerit tempor metus. Maecenas non dapibus dui. Suspendisse cursus nunc sit amet dolor ultricies dictum. 
        Quisque vel dolor feugiat, ultrices tellus quis, consectetur justo. Donec gravida, metus sed dapibus ultricies, 
        purus turpis blandit felis, vitae euismod libero orci vel quam. Mauris sit amet libero mi. Maecenas nec nisi 
        lorem. Curabitur turpis ligula, finibus ac consequat sed, vestibulum a ante.</p>

        <p>Pellentesque vel velit vel urna ultricies viverra. Pellentesque nec sapien eget turpis mollis vestibulum. 
        Ut finibus orci ut metus mollis tincidunt. Cras ut sapien maximus, mattis diam ac, auctor lectus. Nulla 
        consectetur ac sem eu lacinia. Sed tempus ipsum ac arcu consequat vulputate. Morbi ornare viverra ornare.
        Nunc iaculis sem mauris, sit amet efficitur sem rutrum et. In mi felis, accumsan in suscipit nec, commodo 
        quis enim. Mauris urna quam, pellentesque eget justo a, auctor lacinia dolor. Integer in finibus arcu. 
        Phasellus nisi felis, mattis vitae nunc ac, sollicitudin vehicula ipsum. Nulla egestas aliquet est, 
        id porta tortor. Aenean tempus non est eget dignissim.</p>';

        $description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis erat ex, euismod sit amet arcu at, 
        hendrerit tempor metus.';

        for ($i = 0; $i < 3; $i++) {
            $post = new Post();
            $post->setTitle(
                'Some random text '.rand(
                    0,
                    100
                )
            );
            $post->setSlug(
                'some-random-text-'.rand(
                    0,
                    100
                )
            );
            $post->setDescription($description);
            $post->setText($text);
            $post->setCreated(new \DateTime());
            $post->setUser($this->getReference('admin'));
            $post->setTemplate($this->getReference('blog'));
            $post->setActive(true);
            $post->setLocale($locale);
            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * Fixtures data of categories.
     *
     * @param ObjectManager $manager
     */
    private function loadCategories(ObjectManager $manager)
    {
        $locale = self::DEFAULT_LOCALE;

        $data = [['name' => 'Web Developer', 'slug' => 'web-developer'],
                ['name' => 'Movie', 'slug' => 'movie'],
        ];

        foreach ($data as $item) {

            $category = new Category();
            $category->setName($item['name']);
            $category->setSlug($item['slug']);
            $category->setCreated(new \DateTime());
            $category->setLocale($locale);
            $manager->persist($category);
        }

        $manager->flush();
    }

    /**
     * Fixtures data of users.
     *
     * @param ObjectManager $manager
     */
    private function loadUsers(ObjectManager $manager)
    {
        // Admin User.
        $user = new User();
        $user->setUsername('admin');
        $user->setFullName('Admin Manager');
        $user->setEmail('admin.manager@example.com');
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'admin'
            )
        );
        $user->setRoles(['ROLE_ADMIN']);
        $user->setCreated(new \DateTime());
        $this->addReference('admin', $user);
        $manager->persist($user);

        $manager->flush();
    }

    /**
     * Fixtures data of templates.
     *
     * @param ObjectManager $manager
     */
    private function loadTemplates(ObjectManager $manager)
    {
        $data = [
            [
                'name' => 'Blog',
                'view' => 'post/blog.html.twig',
                'reference' => 'blog',
                'slug' => 'blog',
            ],
            [
                'name' => 'Gallery',
                'view' => 'post/gallery.html.twig',
                'reference' => 'gallery',
                'slug' => 'gallery',
            ],
        ];

        foreach ($data as $item) {
            $template = new Template();
            $template->setName($item['name']);
            $template->setView($item['view']);
            $template->setCreated(new \DateTime());

            # More about reference methods see here:
            # https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html#sharing-objects-between-fixtures
            $this->addReference($item['reference'], $template);
            $manager->persist($template);
        }

        $manager->flush();
    }

    /**
     * Fixtures data of media types.
     *
     * @param ObjectManager $manager
     */
    private function loadMediaTypes(ObjectManager $manager)
    {
        $data = [
            [
                'name' => 'Image',
                'slug' => 'image',
            ],
            [
                'name' => 'Video',
                'slug' => 'video',
            ],
        ];

        foreach ($data as $item) {
            $mediaType = new MediaType();
            $mediaType->setName($item['name']);
            $mediaType->setSlug($item['slug']);
            $mediaType->setCreated(new \DateTime());
            $manager->persist($mediaType);
        }

        $manager->flush();
    }

    /**
     * Create one row of setting.
     *
     * @param ObjectManager $manager
     */
    private function loadSetting(ObjectManager $manager)
    {
        $setting = new Setting();
        $setting->setUpdated(new \DateTime());
        $manager->persist($setting);

        $manager->flush();
    }
}
