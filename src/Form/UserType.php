<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class)
                ->add('email', EmailType::class)
                ->add('plainPassword', RepeatedType::class, [
                      'mapped' => false,
                      'type' => PasswordType::class,
                      'constraints' => [
                            new NotBlank([
                                'message' => 'Choose a password!'
                            ]),
                            new Length([
                                'min' => 5,
                                'minMessage' => 'Come on, you can think of a password longer than that!'
                            ])
                      ],
                      'first_options' => ['label' => 'Password'],
                      'second_options' => ['label' => 'Repeat Password']
                ])
            ->add('imageFile',
                FileType::class,
                ['label' => 'Image file for the post banner',
                    'mapped' => false,
                    'required' => false
                ]
            )
            ->add('fullname', TextType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}