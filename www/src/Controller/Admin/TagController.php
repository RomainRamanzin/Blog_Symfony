<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_admin_')]
class TagController extends AbstractController
{
     #[Route('/admin/tags', name: 'tags_list')]
     public function tagsList(TagRepository $tagRepository): Response
     {
          $tags = $tagRepository->findAll();
          return $this->render('admin/tag/list.html.twig', [
               'tags' => $tags,
          ]);
     }

     #[Route('/admin/tag/{id}/view', name: 'tag_view')]
     public function tagView(Tag $tag): Response
     {
          return $this->render('admin/tag/view.html.twig', [
               'tag' => $tag,
          ]);
     }

     #[Route('/admin/tag/create', name: 'tag_create')]
     #[Route('/admin/tag/{id}/update', name: 'tag_update')]
     public function tagCreate(Tag $tag = null, Request $request, EntityManagerInterface $entityManager): Response
     {
          if ($tag === null) {
               $tag = new Tag();
          }

          $form = $this->createForm(TagType::class, $tag);

          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {

               $entityManager->persist($tag);
               $entityManager->flush();

               return $this->redirectToRoute('app_admin_tags_list');
          }

          return $this->render('admin/tag/create.html.twig', [
               'createTagForm' => $form,
          ]);
     }

     #[Route('/admin/tag/{id}/delete', name: 'tag_delete')]
     public function tagDelete(Tag $tag, EntityManagerInterface $entityManager): Response
     {
          $entityManager->remove($tag);
          $entityManager->flush();

          return $this->redirectToRoute('app_admin_tags_list');
     }
}
