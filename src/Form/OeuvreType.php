<?php

namespace App\Form;

use App\Entity\Oeuvre;
use App\Entity\Technique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class OeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('dateCreation', null, [
                'widget' => 'single_text',
            ])
            ->add('description')
            ->add('estDetruite')
            ->add('lieu', TextType::class, [
                'label' => 'Lieu (Ville, Adresse...)',
                'required' => true,
            ])
            ->add('techniques', EntityType::class, [
                'class' => Technique::class,
                'choice_label' => 'nom',
                'multiple' => true,
            ])
            ->add('nomFichier', FileType::class, [
                'label' => 'Photo de l\'œuvre (Image)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '5M',
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        mimeTypesMessage: 'Veuillez uploader une image valide (JPEG, PNG, WEBP)',
                    )
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvre::class,
        ]);
    }
}
