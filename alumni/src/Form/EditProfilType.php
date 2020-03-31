<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Length;

class EditProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photo', FileType::class,[
                'data_class' => null,
                'required' => false
            ])
            ->add('login',TextType::class)
            ->add('name',TextType::class)
            ->add('nickname',TextType::class)
            ->add('department',TextType::class)
            ->add('promo',TextType::class)
            ->add('email',EmailType::class)
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Homme' => 1,
                    'Femme' => 2,
                    'Autre' => 3
                ],
            ])
            ->add('Modifier_le_profil',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
