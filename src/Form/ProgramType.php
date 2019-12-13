<?php

namespace App\Form;

use App\Entity\Program;
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
                'choice_label' => 'name'
            ]);
        /*'choice_label' => 'name'
    ]);*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
