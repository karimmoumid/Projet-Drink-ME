<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class,[
                'label' => 'Numéro :',
            ])
            ->add('street', TextType::class,[
                'label' => 'Rue :',
            ])
            ->add('postal_code', TextType::class,[
                'label' => 'Code postal :',
            ])
            ->add('city', TextType::class,[
                'label' => 'Ville :',
            ])
            ->add('apt', TextType::class,[
                'label' => 'Complement d\'adresse :',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
