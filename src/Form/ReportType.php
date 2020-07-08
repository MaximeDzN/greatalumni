<?php

namespace App\Form;

use App\Entity\Report;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('local_erreur',TextType::class)
            ->add('alert_level', ChoiceType::class, [
                'choices'  => [
                    'Faible' => "Faible",
                    'Moyen' =>"Moyen" ,
                    'Elever' => "Elever"
                ]
            ])
            ->add('comments',TextType::class,)
           // ->add('date', DateType::class, )
            ->add('envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
            'method'=> 'get',
            'csrf_protection'=> false,
        ]);
    }
}
