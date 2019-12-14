<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', null, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Année',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Description',
                ],
            ])/*->add('program', null, [
                'choice_label' => 'title'
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
