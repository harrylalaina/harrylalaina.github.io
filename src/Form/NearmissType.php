<?php

namespace App\Form;

use App\Entity\Niveau;
use App\Entity\NearMiss;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NearmissType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('niveau', EntityType::class, [
                'class' => Niveau::class,
                'choice_label' => 'type_niveau',
                'label' => 'Niveau du near miss',
                'disabled' => false
            ])
            ->add('employe', EmployeType::class)
            ->add('titre', TextType::class)
            ->add('description', TextareaType::class, ['label' => "Description de l'incident"])
            ->add('action_immediate', TextareaType::class, ['label' => 'Action immédiate'])
            ->add('action_prevention', TextareaType::class, ['label' => 'Action de prévention (*)'])
            ->add('niveau_risque', ChoiceType::class, [
                'label' => 'Niveau de risque',
                'choices' => [
                    'Faible' => 'Faible',
                    'Significatif' => 'Significatif',
                    'Elevé' => 'Elevé',
                    'Inacceptable' => 'Inacceptable'
                ]
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'designation',
                'label' => 'Categorie du near miss'
            ])
            ->add('tache_affecte', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'label' => "Est-ce-qu'il y a d'autres zones de travail ou tâches qui sont affectées?",
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]

            ])
            ->add('support', TextType::class, ['label' => 'Support (*)'])
            ->add('action_possible', TextType::class, ['label' => 'Action possible (*)'])
            ->add('responsable', TextType::class, ['label' => 'Responsable (*)'])
            ->add('action_possible01', TextType::class, ['label' => ' '])
            ->add('responsable01', TextType::class, ['label' => ' '])
            ->add('action_possible02', TextType::class, ['label' => ' '])
            ->add('responsable02', TextType::class, ['label' => ' '])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Preuve (*)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'delete',
                'download_uri' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NearMiss::class,
        ]);
    }
}
