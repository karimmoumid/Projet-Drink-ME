<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('famillyname', options: ['label' => 'Nom'])
            ->add('firstname', options: ['label' => 'Prénom'])
            ->add('email');

        // Le mot de passe est optionnel si on édite
        $builder->add('plainPassword', PasswordType::class, [
            'mapped' => false,
            'label' => 'Mot de passe',
            'required' => !$options['is_edit'],
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => $options['is_edit'] ? [] : [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    'max' => 4096,
                ]),
            ],
        ]);

        // N'affiche la case à cocher que si on est en création
        if (!$options['is_edit']) {
            $builder->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J\'accepte les termes et conditions',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes.',
                    ]),
                ],
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false, // false = formulaire pour inscription
        ]);
    }
}
