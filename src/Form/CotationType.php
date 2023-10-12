<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Critere;
use App\Entity\Cotation;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note')
            ->add('commentaire')
            ->add('dateCotation')
            // champs de liason qu'ON VA CACHER DANS LA VUE AVEC DU CSS
            // Ce seront des listes déroulantes qui auront l'entité fixée dans le controller
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom'
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom'
            ])
            ->add('critere', EntityType::class, [
                'class' => Critere::class,
                'choice_label' => 'questioncritere'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cotation::class,
        ]);
    }
}
