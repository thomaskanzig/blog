<?php

namespace App\Form;

use App\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('google_gtag_id',  TextType::class, [
                'required' => false
            ])
            ->add('url_facebook',  TextType::class, [
                'required' => false
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
