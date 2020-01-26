<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_pages_index")
     */
    public function index()
    {
        return $this->render('admin/pages/index.html.twig');
    }
}
