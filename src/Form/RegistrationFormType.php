<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Validator\Constraints\NoSpaces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                // 'attr' => [
                //     'placeholder' => 'Email',
                // ],
                'label' => false,
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    // 'placeholder' => 'Password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('username', TextType::class, [
                'label' => false, // Ajout du champ username de type TextType
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci d'entrer un nom d'utilisateur",
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => "Votre nom d'utilisateur doit comporter au moins {{ limit }} caractères",
                        'max' => 50,
                    ]),

                ],
            ])
            ->add('pictureFile', VichImageType::class, [
                'required' => false,
                'label' => false, // Remplacez 'Image' par le libellé que vous souhaitez afficher
            ])->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter condition',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ]);
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $user = $event->getData();
            if ($user instanceof User && $user->getPicture() === null) {
                // Par exemple, définir une valeur par défaut si picture est null
                $user->setPicture('user.png');
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
