<?php

namespace App\Controller\Admin;

use App\Form\UserAccessType;
use App\Form\UserProfileType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Service\UploaderHelper;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    /**
     * @Route("/admin/users", name="admin_user_index")
     */
    public function index(UserRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $q = $request->query->get('q'); /* get text search */
        $queryBuilder = $repository->getWithSearchQueryBuilder($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/* page number */,
            10 /* limit per page */
        );

        return $this->render('admin/user/index.html.twig', [
            'users' => $pagination,
        ]);
    }

    /**
     * @Route("/admin/user/add", name="admin_user_add")
     */
    public function add(EntityManagerInterface $em, Request $request, UploaderHelper $uploaderHelper, UserPasswordEncoderInterface $passwordEncoder)
    {

        // Create the form based on the FormType we need.
        $userForm = $this->createForm(UserType::class);

        // Ask the form to handle the current request.
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            // Get data of form.
            $user = $userForm->getData();

            // Set the password.
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $userForm['plainPassword']->getData()
            ));

            // Send an image file an store in /public.
            $uploadedFile = $userForm['imageFile']->getData();
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
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/add.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    /**
     * @Route("/admin/user/{id}/profile", name="admin_user_profile")
     */
    public function profile(User $user, EntityManagerInterface $em, Request $request, UploaderHelper $uploaderHelper)
    {

        // Create the form based on the FormType we need.
        $userForm = $this->createForm(UserProfileType::class, $user);

        // Ask the form to handle the current request.
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            // Send an image file an store in /public.
            $uploadedFile = $userForm['imageFile']->getData();
            if ($uploadedFile) {
                $newFile = $uploaderHelper->uploadMedia($uploadedFile);
                $user->setUrlAvatar($newFile['file']);
            }

            // To save.
            $em->persist($user);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', 'User Updated!');

            // Redirect to another page.
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/profile.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    /**
     * @Route("/admin/user/{id}/access", name="admin_user_access")
     */
    public function access(User $user, EntityManagerInterface $em, Request $request, UploaderHelper $uploaderHelper, UserPasswordEncoderInterface $passwordEncoder)
    {

        // Create the form based on the FormType we need.
        $userForm = $this->createForm(UserAccessType::class, $user);

        // Ask the form to handle the current request.
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            // Set the password.
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $userForm['plainPassword']->getData()
            ));

            // To save.
            $em->persist($user);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', 'User access is updated!');

            // Redirect to another page.
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/access.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    /**
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     */
    public function delete(User $user, EntityManagerInterface $em)
    {

        if ($user instanceof User) {

            // Set an date to delete.
            $user->setDeleted(new \DateTime());

            // To save.
            $em->persist($user);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', 'User Deleted!');
        }

        // Redirect to list.
        return $this->redirectToRoute('admin_user_index');
    }
}
