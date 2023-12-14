<?php

namespace App\Form;

use App\Entity\Images;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imagefile', FileType::class, [
                'label' => 'Ajouter votre image',
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'extensions' => ['jpeg', 'gif', 'png'],
                        'extensionsMessage' => 'l\'extension de votre fichier n\'est pas valid ({{ extension }}). Les extensions autorisées sont {{ extensions }}',
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} {{ suffix }}). La taille maximale autorisée est {{ limit }} {{ suffix }}'
                    ])
                ]
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'image',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
                'help' => 'Veuillez faire un titre court max de 10 mots',
                'help_attr' => ['class' => 'form-text'],
                'required' => false
            ])
            ->add('slug', TextType::class,[
                'label' => 'Nom court de l\'image',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
                'help' => 'Veuillez indiquer un court nom, max 3 mots il sera important pour la suite',
                'help_attr' => ['class' => 'form-text'],
                'required' => false
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
                'help' => 'Une petite déscription serais sympas',
                'help_attr' => ['class' => 'form-text'],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}
