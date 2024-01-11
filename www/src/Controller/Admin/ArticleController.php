<?php

namespace App\Controller\Admin;

use App\Entity\Content;
use App\Entity\Media;
use App\Entity\Post;
use App\Entity\User;

use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_admin_')]
class ArticleController extends AbstractController
{
     #[Route('/admin/articles', name: 'article_list')]
     public function articleList(EntityManagerInterface $entityManager): Response
     {
          $articleList = $entityManager->getRepository(Post::class)->findBy([
               'type' => 'article',
               'deletedAt' => null
          ]);

          return $this->render('admin/article/list.html.twig', ['articleList' => $articleList]);
     }

     #[Route('/admin/articles/create', name: 'article_create')]
     #[Route('/admin/articles/{id}/update', name: 'article_update')]
     public function articleCreate(Post $post = null, Request $request, EntityManagerInterface $entityManager): Response
     {
          $user = $this->getUser();

          if ($post === null) {
               $post = new Post();
               $post->setCreatedAt(new \DateTimeImmutable('now'));
               $post->setType('article');
               $post->setAuthor($user);
          } else {
               $edit = true;
          }

          $contentParent = $post->getContents()->isEmpty() === false ? $post->getContents()->last() : null;

          $form = $this->createForm(ArticleType::class, $post, [
               'contentData' => $contentParent ? $contentParent->getData() : "",
          ]);

          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
               // traitement des données reçues
               $data = $form->get('data')->getData();
               $media = $form->get('media')->getData();

               $content = new Content();
               $content->setPost($post);
               $content->setData($data);

               foreach ($media as $mediaId) {
                    $mediaObj = $entityManager->getRepository(Media::class)->findoneby(['id' => $mediaId]);
                    $content->addMedium($mediaObj);
               }

               // ajouter le parent si il y en a un
               if ($contentParent) {
                    $content->setParent($contentParent);
               }

               $entityManager->persist($post);
               $entityManager->persist($content);
               $entityManager->flush();

               return $this->redirectToRoute('app_admin_article_list');
          }

          return $this->render('admin/article/create.html.twig', [
               'createArticleForm' => $form,
               'edit' => $edit ?? false,
          ]);
     }

     #[Route('/admin/articles/{id}', name: 'article_view')]
     public function articleView($id, EntityManagerInterface $entityManager): Response
     {
          $article = $entityManager->getRepository(Post::class)->find(intval($id));

          return $this->render('admin/article/view.html.twig', ['article' => $article]);
     }

     // #[Route('/admin/articles/{id}/delete', name: 'article_delete')]
     // public function articleDelete($id, EntityManagerInterface $entityManager): Response
     // {
     //     $article = $entityManager->getRepository(Post::class)->find(intval($id));

     //     $article->setDeletedAt(new \DateTimeImmutable('now'));

     //     $entityManager->persist($article);
     //     $entityManager->flush();

     //     return $this->redirectToRoute('app_admin_article_list');
     // }
}
