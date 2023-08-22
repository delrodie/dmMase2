<?php

namespace App\Form;

use App\Entity\Portrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PortraitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>"off"],
                'label' => "Nom *"
            ])
            ->add('prenoms', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>"off"],
                'label' => "Prenoms *"
            ])
            ->add('fonction', TextType::class,[
                'attr'=>['class'=>'form-control', 'autocomplete'=>"off"],
                'label' => "Fonction *"
            ])
            ->add('biographie', TextareaType::class,[
                'attr' => ['class' => 'contenu', 'style'=>null],
                'required' => false
            ])
            ->add('media', FileType::class,[
                'attr'=>['class'=>"dropify", 'data-preview' => ".preview"],
                'label' => "Télécharger la photo ",
                'mapped' => false,
                'multiple' => false,
                'constraints' => [
                    new File([
                        'maxSize' => "20000k",
                        'mimeTypes' =>[
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/webp',
                        ],
                        //'mimeTypesMessage' => "Votre fichier doit être de type image",
                        //'maxSizeMessage' => "La taille de votre image doit être inférieure à 2Mo",
                    ])
                ],
                'required' => false
            ])
            ->add('ordre', ChoiceType::class,[
                'attr'=>['class'=>'form-select select2'],
                'choices' => [
                    '1' => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    6=>6,
                    7=>7,
                    8=>8,
                    9=>9,
                    10=>10,
                    11=>11,
                    12=>12,
                    13=>13,
                    14=>14,
                    15=>15,
                    16=>16,
                    17=>17,
                    18=>18,
                    19=>19,
                    20=>20
                ],
                'label' => "Ordre d'affiche *"
            ])
            //->add('slug')
            ->add('instance', null, [
                'attr'=>['class'=>'form-select'],
                'label' => "Instance *"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Portrait::class,
        ]);
    }
}
