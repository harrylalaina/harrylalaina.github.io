<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Employe;
use App\Entity\Year;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class HistoNearmissType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mots', SearchType::class, [
                'label' => "Mot à chercher",
                'required' => false
            ])
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'name',
                'label' => "Employe",
                'required' => false
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'designation',
                'label' => "Catégorie",
                'required' => false
            ])
            ->add('year', EntityType::class, [
                'class' => Year::class,
                'choice_label' => 'libelle',
                'label' => "Année",
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
