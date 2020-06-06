<?php

namespace App\Form;

use App\Entity\Homepage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('sidebar_about_me_active', CheckboxType::class,[
                'required' => false,
                'label'     => $this->translator->trans('app.general.form.label.active'),
            ])
            ->add('sidebar_about_me_text', TextareaType::class,[
                'label' => $this->translator->trans('app.general.form.label.text'),
                'attr' => [
                    'rows' => 4
                ]
            ])
            ->add('sidebar_about_me_photo_file',
                FileType::class, [
                    'label' => $this->translator->trans('app.general.form.label.photo'),
                    'mapped' => false,
                    'required' => false
                ]
            )
            ->add('sidebar_about_me_url_facebook',  TextType::class,[
                'label' => 'Facebook URL',
                'required' => false,
            ])
            ->add('sidebar_about_me_url_instagram',  TextType::class,[
                'label' => 'Instagram URL',
                'required' => false,
            ])
            ->add('sidebar_about_me_url_youtube',  TextType::class,[
                'label' => 'Youtube URL',
                'required' => false,
            ])
            ->add('sidebar_about_me_url_linkedin',  TextType::class,[
                'label' => 'Linkedin URL',
                'required' => false,
            ])
            ->add('sidebar_about_me_url_github',  TextType::class,[
                'label' => 'Github URL',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Homepage::class,
        ]);
    }
}
