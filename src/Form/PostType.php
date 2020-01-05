<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Template;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',  TextType::class)
            ->add('description', TextareaType::class)
            ->add('active', CheckboxType::class,[
                'required' => false
            ])
            ->add('text', TextareaType::class, [
                'required' => false // Is false, for fix an bug in ckeditor.
            ])
            ->add('imageFile',
                  FileType::class,
                  ['label' => 'Image file for the post banner',
                   'mapped' => false,
                   'required' => false
                  ]
            )
            ->add('categories', EntityType::class, [
                'label'     => 'Choose the categories',
                'class'     => Category::class,
                'choice_label' => 'name',
                'expanded'  => true,
                'multiple'  => true,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->findAllActive();
                },
            ])
            ->add('user', EntityType::class, [
                'label'     => 'Who is the creator?',
                'class'     => User::class,
                'choice_label' => 'fullname',
                'placeholder' => 'Select user:',
                'required' => false
            ])
            ->add('published',  DateTimeType::class)
            ->add('template', EntityType::class, [
                'label'     => 'Which template?',
                'class'     => Template::class,
                'choice_label' => 'name',
                'placeholder' => 'Select template:',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
