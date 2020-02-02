<?php

namespace App\Form;

use App\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\Translation\TranslatorInterface;

class SettingType extends AbstractType
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
            ->add('google_gtag_id',  TextType::class, [
                'required' => false
            ])
            ->add('url_facebook',  TextType::class, [
                'required' => false
            ])
            ->add('app_id_facebook',  TextType::class, [
                'required' => false
            ])
            ->add('show_comments_facebook',  CheckboxType::class,[
                'required' => false,
                'label'     => $this->translator->trans('admin.settings.form.label.show_comments_facebook'),
            ])
            ->add('url_instagram',  TextType::class, [
                'required' => false
            ])
            ->add('url_linkedin',  TextType::class, [
                'required' => false
            ])
            ->add('url_github',  TextType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
