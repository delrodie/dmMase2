<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AdhesionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('raisonSociale', TextType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'autocomplete'=>"off"],
                'label' => "Raison sociale"
            ])
            ->add('siege', TextType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'autocomplete'=>"off"],
                'label' => "Siège"
            ])
            ->add('adresse', TextType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'autocomplete'=>"off"],
                "label" => "Adresse"
            ])
            ->add('telephone', TelType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'autocomplete'=>"off"],
                "label" => "Telephone"
            ])
            ->add('email', EmailType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'autocomplete'=>"off"],
                "label" => "Email"
            ])
            ->add('annee', IntegerType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'autocomplete'=>"off"],
                "label" => "Année"
            ])
            ->add('domaine', TextareaType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'autocomplete'=>"off"],
                "label" => "Domaine"
            ])
            //->add('type')
            ->add('logo', FileType::class,[
                'attr'=>['class'=>"dropify", 'data-preview' => ".preview"],
                'label' => "Télécharger le logo ",
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
                'required' => true
            ])
            ->add('rc', TextType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'autocomplete'=>"off"],
                "label" => "Registre de commerce"
            ])
            ->add('cc', TextType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'autocomplete'=>"off"],
                "label" => "Compte contribuable"
            ])
            //->add('slug')
            //->add('valid')
            //->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
