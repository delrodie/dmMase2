<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse', TextType::class,['attr'=>['class'=>'form-control']])
            ->add('bp', TextType::class,[
                'attr'=>['class'=>'form-control'],
                'label' => "Boîte Postale"
            ])
            ->add('phone', TelType::class,[
                'attr'=>['class'=>'form-control'],
                'label' => "Telephone"
            ])
            ->add('email', EmailType::class,['attr'=>['class'=>'form-control']])
            ->add('map', UrlType::class,[
                'attr'=>['class'=>'form-control'],
                'label' => "Lien Google Map"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
