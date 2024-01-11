<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null , [
                'label' => 'Nom de la catégorie',
                'required' => true
            ])
            ->add('parent', null, [
                'label' => 'Catégorie parente (optionnel)',
                'required' => false,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.name != :name')
                        ->setParameter('name', $options['categoryName'] ?: '');
                },
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'categoryName' => null,
        ]);
    }
}
