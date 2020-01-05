<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends AbstractController
{

    /**
     * @Route("/about-me", name="pages_about_me")
     */
    public function aboutme()
    {

        return $this->render('pages/about-me.html.twig');
    }


    /**
     * @Route("/contact", name="pages_contact")
     */
    public function contact()
    {

        return $this->render('pages/contact.html.twig');
    }

    /**
     * @Route("/register", name="pages_register")
     */
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

            // Redirect to another page.
            return $this->redirectToRoute('blog_index');
        }

        return $this->render('pages/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
