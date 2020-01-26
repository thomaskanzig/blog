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
use Symfony\Contracts\Translation\TranslatorInterface;

class UserType extends AbstractType {
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,[
                'label'     => $this->translator->trans('app.general.form.label.username'),
            ])
            ->add('email', EmailType::class,[
                'label'     => $this->translator->trans('app.general.form.label.email'),
            ])
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
                  'first_options' => ['label' => $this->translator->trans('app.general.form.label.password')],
                  'second_options' => ['label' => $this->translator->trans('app.general.form.label.repeat_password')]
            ])
            ->add('imageFile',
                FileType::class,
                ['label' => $this->translator->trans('admin.users.form.label.add_avatar'),
                    'mapped' => false,
                    'required' => false
                ]
            )
            ->add('fullname', TextType::class,[
                'label'     => $this->translator->trans('app.general.form.label.fullname'),
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}