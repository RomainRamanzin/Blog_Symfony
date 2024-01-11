<?php

namespace App\Form;

use App\Entity\Content;
use App\Entity\Media;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['label' => 'Titre'])
            // ->add('type')
            ->add('file', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false,
                'required' => false,
                'constraints' =>  [
                    new ConstraintsFile([
                        'maxSize' => '1024M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir un fichier valide (jpg, png)',
                    ])
                ]
                
            ])
            ->add('alt', null, ['label' => 'Texte alternatif'])
            // ->add(
            //     'contents',
            //     EntityType::class,
            //     [
            //         'class' => Content::class,
            //         'choice_label' => 'data',
            //         'label' => 'Contenu',
            //         'multiple' => true,
            //         'expanded' => true,
            //     ]
            // )
            ->add('submit', SubmitType::class, ['label' => 'Enregistrer']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
