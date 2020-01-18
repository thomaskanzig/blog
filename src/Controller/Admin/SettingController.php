<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SettingType;
use Doctrine\ORM\EntityManagerInterface;

class SettingController extends AbstractController
{
    /**
     * @Route("/admin/setting", name="admin_setting_index")
     *
     * @param SettingRepository $repository
     * @param EntityManagerInterface $em
     * @param Request $request
     *
     * @return Response
     */
    public function index(SettingRepository $repository, EntityManagerInterface $em, Request $request)
    {
        /** @var Setting $setting */
        $setting = $repository->findOne();

        // Create the form based on the FormType we need.
        $settingForm = $this->createForm(SettingType::class, $setting);

        // Ask the form to handle the current request.
        $settingForm->handleRequest($request);

        if ($settingForm->isSubmitted() && $settingForm->isValid()) {

            // To save.
            $em->persist($setting);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', 'Setting Updated!');

            // Redirect to another page.
            return $this->redirectToRoute('admin_setting_index');
        }

        return $this->render('admin/setting/index.html.twig', [
            'settingForm' => $settingForm->createView()
        ]);
    }

}
