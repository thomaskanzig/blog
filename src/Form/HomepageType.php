<?php

namespace App\Form;

use App\Entity\Homepage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomepageType extends AbstractType
{
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
            ->add('limit_highlights',  ChoiceType::class, [
                'label' => $this->translator->trans('admin.homepage.form.label.limit_highlights'),
                'choices' => [
                    1 => 1,
                    2 => 2,
                    3 => 3
                ]
            ])
            ->add('sidebar_about_me_text', TextareaType::class,[
                'label' => $this->translator->trans('app.general.form.label.text'),
            ])
            ->add('sidebar_about_me_photo_file',
                FileType::class, [
                    'label' => $this->translator->trans('app.general.form.label.photo'),
                    'mapped' => false,
                    'required' => false
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Homepage::class,
        ]);
    }
}
