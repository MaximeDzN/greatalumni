<?php

namespace App\Form;
use App\Entity\Message;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class PrivateMessageType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$user = $options['empty_data'];
		
        $builder
			->add('content', TextareaType::class, array(
				'label' => 'Message',
				'required' => 'required',
				'attr' => array(
					'class' => 'form-control'
				)
			))
            ->add('Envoyer', SubmitType::class, array(
				"attr" => array(
					"class" => "btn btn-success"
				)
			))
        ;
                }
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => Message::class,
            ]);
        }
    }
