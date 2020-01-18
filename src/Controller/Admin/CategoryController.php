<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use Cocur\Slugify\Slugify;

class CategoryController extends AbstractController
{

    /**
     * @Route("/admin/categories", name="admin_category_index")
     */
    public function index(CategoryRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $q = $request->query->get('q'); /* get text search */
        $queryBuilder = $repository->getWithSearchQueryBuilder($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/* page number */,
            10 /* limit per page */
        );

        return $this->render('admin/category/index.html.twig', [
            'categories' => $pagination,
        ]);
    }

    /**
     * @Route("/admin/category/add", name="admin_category_add")
     */
    public function add(EntityManagerInterface $em, Request $request)
    {
        // Create the form based on the FormType we need.
        $categoryForm = $this->createForm(CategoryType::class);

        // Ask the form to handle the current request.
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            // Get data of form.
            $category = $categoryForm->getData();

            // Set some others information of category.
            $slugify = new Slugify();
            $category->setSlug($slugify->slugify($category->getName()));
            $category->setCreated(new \DateTime());

            // To save.
            $em->persist($category);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', 'Category Created!');

            // Redirect to another page.
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/add.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);
    }


    /**
     * @Route("/admin/category/{id}/edit", name="admin_category_edit")
     */
    public function edit(Category $category, EntityManagerInterface $em, Request $request)
    {
        // Create the form based on the FormType we need.
        $categoryForm = $this->createForm(CategoryType::class, $category);

        // Ask the form to handle the current request.
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            // To save.
            $em->persist($category);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', 'Category Updated!');

            // Redirect to another page.
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/index.html.twig', [
            'categoryForm' => $categoryForm->createView(),
            'id' => $category->getId()
        ]);
    }

    /**
     * @Route("/admin/category/{id}/delete", name="admin_category_delete")
     */
    public function delete(Category $category, EntityManagerInterface $em)
    {
        if ($category instanceof Category) {

            // Set an date to delete.
            $category->setDeleted(new \DateTime());

            // To save.
            $em->persist($category);
            $em->flush();

            // Set an message after save.
            $this->addFlash('success', 'Category Deleted!');
        }

        // Redirect to list.
        return $this->redirectToRoute('admin_category_index');
    }
}
