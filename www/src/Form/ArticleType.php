<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Post;
use App\Entity\Tag;
use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ArticleType extends AbstractType
{
    private  MediaRepository $mediaRepository;

    public function __construct(MediaRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $mediaChoices = [];
        foreach ($this->mediaRepository->findAll() as $media) {
            $mediaChoices ['/uploads/'.$media->getPath()] = $media->getId();
        }

        $post = $options['data'];
        $lastContent = $post->getContents()->last();
        $selectedMediaList = $lastContent ? $lastContent->getMedia() : [];

        $mediaSelectedChoices = [];
        foreach ($selectedMediaList as $media) {
            $mediaSelectedChoices[] = $media->getId();
        }
        

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'article',
                'required' => true
            ])
            ->add('data', TextareaType::class, [
                'data' => $options['contentData'],
                'mapped' => false,
                'required' => true,
            ])
            ->add('publishedAt')
            ->add('slug')
            ->add('titleSeo')
            ->add('descriptionSeo')
            ->add('category')
            ->add('media', ChoiceType::class, [
                'choices' => $mediaChoices,
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'label' => 'Image de l\'article',
                'data' => $mediaSelectedChoices,
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'contentData' => ''
        ]);
    }
}
