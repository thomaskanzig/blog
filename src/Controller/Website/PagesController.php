<?php

namespace App\Controller\Website;

use App\Entity\Post;
use App\Entity\User;
use App\Form\UserType;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class PagesController extends AbstractController
{
    /**
     * Homepage
     *
     * @param EntityManagerInterface $em
     * @param Request $request
     *
     * @return Response
     */
    public function homepage(EntityManagerInterface $em, Request $request)
    {
        /** @var Post[] $highlights */
        $highlights = $em->getRepository(Post::class)->findBy([
                'highlight' => true,
                'active' => true,
                'locale' => $request->getLocale()
            ], ['published' => 'DESC'], 3);


        /** @var Post[] $posts */
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findWithExcluded([
                'limit' => 3,
                'locale' => $request->getLocale(),
            ], [8,7]);

        return $this->render('pages/homepage.html.twig', [
            'highlights' => $highlights,
        ]);
    }

    public function aboutme()
    {

        return $this->render('pages/about-me.html.twig');
    }

    public function contact()
    {

        return $this->render('pages/contact.html.twig');
    }

    public function register(EntityManagerInterface $em, UploaderHelper $uploaderHelper, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class,
                                  $user
                );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Get data of form.
            $user = $form->getData();

            // Set the password.
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $form['plainPassword']->getData()
            ));

            // Send an image file an store in /public.
            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $newFile = $uploaderHelper->uploadMedia($uploadedFile);
                $user->setUrlAvatar($newFile['file']);
            }

            $user->setRoles(['ROLE_ADMIN']);
            $user->setCreated(new \DateTime());

            // To save.
            $em->persist($user);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', 'User Created!');

            // Redirect to login page.
            return $this->redirectToRoute('security_login');
        }

        return $this->render('pages/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
