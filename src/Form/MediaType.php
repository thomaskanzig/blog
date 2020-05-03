<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\Translation\TranslatorInterface;

class MediaType extends AbstractType
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
            ->add('title',  TextType::class, [
                'label'     => $this->translator->trans('app.general.form.label.title'),
                'required' => false
            ])
            ->add('created',  DateTimeType::class,[
                'label'     => 'Created',
            ])
            ->add('user', EntityType::class, [
                'label'     => $this->translator->trans('admin.general.form.label.who_is_the_creator'),
                'class'     => User::class,
                'choice_label' => 'fullname',
                'placeholder' => $this->translator->trans('app.general.form.label.select'). ':',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
