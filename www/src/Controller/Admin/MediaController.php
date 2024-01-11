<?php

namespace App\Controller\Admin;

use App\Entity\Media;

use App\Form\MediaType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name:'app_admin_')]
class MediaController extends AbstractController
{
    #[Route('/admin/medias', name: 'medias_list')]
    public function mediasList(MediaRepository $mediaRepository): Response
    {
        $medias = $mediaRepository->findAll();
        return $this->render('admin/media/list.html.twig', [
            'medias' => $medias,
        ]);
    }

    #[Route('/admin/medias/{id}/view', name: 'media_view')]
    public function mediaView(Media $media): Response
    {
        return $this->render('admin/media/view.html.twig', [
            'media' => $media,
        ]);
    }

    #[Route('/admin/medias/create', name: 'media_create')]
    #[Route('/admin/medias/{id}/update', name: 'media_update')]
    public function mediacreate(Media $media = null, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($media === null){
            $media = new Media();
        }
        else {
            $edit = true;
        }

        $form = $this->createForm(MediaType::class, $media);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();

            // // Test upload avec un service FileManager
            // $title = $form->get('title')->getData();
            // $slugger = new AsciiSlugger();
            // $fm->upload($file, $media->getTitle(), 'uploads');

            if ($file) {
                $type = $file->getMimeType();
                $fileName = uniqid() . '.' . $file->guessExtension();
                $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $file->move($uploadsDirectory, $fileName);

                try {
                    unlink($uploadsDirectory. '/' .$media->getPath());
                } catch (\Exception $e) {
                }
                $media->setType($type);
                $media->setPath($fileName);
            }
            $media->setUser($this->getUser());
            $entityManager->persist($media);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_medias_list');
        }

        return $this->render('admin/media/create.html.twig', [
            'createMediaForm' => $form,
            'edit' => $edit ?? false,
        ]);
    }

    #[Route('/admin/medias/{id}/delete', name: 'media_delete')]
    public function mediaDelete(Media $media, EntityManagerInterface $entityManager): Response
    {
        $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
        try {
            unlink($uploadsDirectory. '/' .$media->getPath());
        } catch (\Exception $e) {
        }

        $entityManager->remove($media);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_medias_list');
    }
}
