<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;


class NewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('media', FileType::class,[
                'data_class' => null
            ])
            ->add('title', TextType::class,[
                'constraints' => [
                    new Length(['max' => 100,'maxMessage' => 'Votre titre doit faire moins de {{ limit }} caractères']),
                    ]
            ])
            ->add('content',TextareaType::class,[
                'constraints' => [
                    new Length(['min' => 100,'minMessage' => 'Votre article doit faire plus de {{ limit }} caractères']),
                ]
            ])
            ->add('Poster',SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
