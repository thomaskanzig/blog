<?php

namespace App\Controller\Admin;

use App\Entity\Homepage;
use App\Form\HomepageType;
use App\Repository\HomepageRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class PagesController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_pages_index")
     */
    public function index()
    {
        return $this->render('admin/pages/index.html.twig');
    }

    /**
     * @Route("/homepage", name="admin_pages_homepage")
     *
     * @param HomepageRepository $repository
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param UploaderHelper $uploaderHelper
     *
     * @return Response
     */
    public function homepage(
        HomepageRepository $repository,
        EntityManagerInterface $em,
        Request $request,
        TranslatorInterface $translator,
        UploaderHelper $uploaderHelper
    ) {
        /** @var Homepage $homepage */
        $homepage = $repository->findOneBy(['locale' => $request->getLocale()]);

        // If not find correctly homepage, then will be created.
        if (!$homepage) {
            $homepage = new Homepage();
            $homepage->setLocale($request->getLocale());

            $em->persist($homepage);
            $em->flush();
        }

        // Create the form based on the FormType we need.
        $homepageForm = $this->createForm(HomepageType::class, $homepage);

        // Ask the form to handle the current request.
        $homepageForm->handleRequest($request);

        if ($homepageForm->isSubmitted() && $homepageForm->isValid()) {

            // Send an image file an store in /public.
            $uploadedFile = $homepageForm['sidebar_about_me_photo_file']->getData();
            if ($uploadedFile) {
                $newFile = $uploaderHelper->uploadMedia($uploadedFile);
                $homepage->setSidebarAboutMePhoto($newFile['file']);
            }

            // To save.
            $em->persist($homepage);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', $translator->trans('admin.homepage.form.post_updated'));

            // Redirect to another page.
            return $this->redirectToRoute('admin_pages_homepage');
        }

        return $this->render('admin/pages/homepage.html.twig', [
            'homepageForm' => $homepageForm->createView()
        ]);
    }
}
