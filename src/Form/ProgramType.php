<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Program;
use App\Repository\ActorRepository;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Titre',
                ],
            ])
            ->add('summary', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Résumé',
                ],
            ])
            ->add('poster', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Jaquettes',
                ],
            ])
            ->add('category', null, [
                'required' => true,
                'label' => false,
                'attr' => ['class' => 'select'],
                'placeholder' => 'Choisissez votre catégorie..',
                'query_builder' => function (CategoryRepository $category) {
                    return $category->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name'
            ])
            ->add('actors', EntityType::class, [
                'class' => Actor::class,
                'by_reference' => false,
                'required' => true,
                'label' => false,
                'expanded' => false,
                'multiple' => true,
                'attr' => ['class' => 'select', 'data-placeholder' => 'Choisissez vos acteurs..'],
                'query_builder' => function (ActorRepository $actor) {
                    return $actor->createQueryBuilder('a')->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
