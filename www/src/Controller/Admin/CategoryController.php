<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_admin_')]
class CategoryController extends AbstractController
{
     #[Route('/admin/categories', name: 'categories_list')]
     public function categoriesList(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
     {
          $categories = $categoryRepository->findAll();
          return $this->render('admin/category/list.html.twig', ['categories' => $categories]);
     }

     #[Route('/admin/categories/{id}/view', name: 'category_view')]
     public function categoryView($id, EntityManagerInterface $entityManager): Response
     {
          $category = $entityManager->getRepository(Category::class)->find(intval($id));

          return $this->render('admin/category/view.html.twig', ['category' => $category]);
     }

     #[Route('/admin/categories/create', name: 'category_create')]
     #[Route('/admin/categories/{id}/update', name: 'category_update')]
     public function categoryCreate(Category $category = null, Request $request, EntityManagerInterface $entityManager): Response
     {

          if ($category === null) {
               $category = new Category();
          }

          $form = $this->createForm(CategoryType::class, $category, [
               'categoryName' => $category->getName(),
          ]);

          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {

               $entityManager->persist($category);
               $entityManager->flush();

               return $this->redirectToRoute('app_admin_categories_list');
          }

          return $this->render('admin/category/create.html.twig', [
               'createCategoryForm' => $form
          ]);
     }

     #[Route('/admin/categories/{id}/delete', name: 'category_delete')]
     public function categoryDelete(Category $category, EntityManagerInterface $entityManager): Response
     {
          $entityManager->remove($category);
          $entityManager->flush();

          return $this->redirectToRoute('app_admin_categories_list');
     }
}
