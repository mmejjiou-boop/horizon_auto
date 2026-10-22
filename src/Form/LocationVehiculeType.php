<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\LocationVehicule;
use App\Entity\Option;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationVehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'placeholder' => '-- Choisir un client --',
            ])
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'label' => 'Véhicule',
                'placeholder' => '-- Choisir un véhicule --',
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
            ])
            ->add('dateFinPrevue', DateType::class, [
                'label' => 'Date de fin prévue',
                'widget' => 'single_text',
            ])
            ->add('dateRetourEffective', DateType::class, [
                'label' => 'Date de retour effective',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('montantTotal', MoneyType::class, [
                'label' => 'Montant total',
                'required' => false,
            ])
            // le contrôleur crée les LocationOption avec le prix du moment
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LocationVehicule::class,
        ]);
    }
}
