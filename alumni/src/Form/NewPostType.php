<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\PostType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
            ->add('title', TextType::class, ['label' => 'Titre du post'])
            ->add('Content', TextareaType::class, ['label' => 'description', 'attr' => [
                'rows' => '10'
            ]])
            
            ->add('PostType', EntityType::class, [
                'class' => PostType::class,
                'choice_label' => 'title',
                'label' => 'choisissez une catégorie'
            ])
            ->add('Poster',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
