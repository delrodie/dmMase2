<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageInternauteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Nom"]])
            ->add('email', EmailType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Email"]])
            ->add('objet', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Objet"]])
            ->add('contenu', TextareaType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Contenu", 'style'=>"height:150px"]])
            //->add('lecture')
            //->add('reponse')
            //->add('sendAt')
            //->add('replyAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
