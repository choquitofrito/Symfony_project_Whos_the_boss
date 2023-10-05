<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('secteur', TextType::class) // CollectionType?? à lier avec un array qui existe dans Entreprise Fixtures $arraySecteur = ['Agroalimentaire', 'Banque/Assurance', 'Bois/Papier/Imprimerie', 'BTP', 'Chimie', 'Commerce/Distribution', 'Édition/Communication', 'Electronique', 'Études et Conseils', 'Industrie pharmaceutique', 'Informatique', 'Équipements/Automobile', 'Metallurgie', 'Plastique', 'Services', 'Textile', 'Transport/Logistique'];
            ->add('emplacement', TextType::class)
            ->add('image',FileType::class)
            ->add('description', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class
        ]);
    }
}