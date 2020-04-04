<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Template;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PostType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RequestStack
     */
    private $request;

    public function __construct(
        TranslatorInterface $translator,
        RequestStack $request
    ) {
        $this->translator = $translator;
        $this->request = $request;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',  TextType::class,[
                'label'     => $this->translator->trans('app.general.form.label.title'),
            ])
            ->add('slug',  TextType::class,[
                'label'     => $this->translator->trans('app.general.form.label.slug'),
            ])
            ->add('description', TextareaType::class,[
                'label'     => $this->translator->trans('app.general.form.label.description'),
            ])
            ->add('active', CheckboxType::class,[
                'required' => false,
                'label'     => $this->translator->trans('app.general.form.label.active'),
            ])
            ->add('text', TextareaType::class, [
                'required' => false, // Is false, for fix an bug in ckeditor.
                'label'     => $this->translator->trans('app.general.form.label.text'),
            ])
            ->add('imageFile',
                  FileType::class, [
                      'label' => $this->translator->trans('admin.posts.form.label.image_file_banner'),
                      'mapped' => false,
                      'required' => false
                  ]
            )
            ->add('categories', EntityType::class, [
                'label'     => $this->translator->trans('admin.posts.form.label.choose_the_categories'),
                'class'     => Category::class,
                'choice_label' => 'name',
                'expanded'  => true,
                'multiple'  => true,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->findAllActive([
                        'locale' => $this->request->getCurrentRequest()->getLocale()
                    ]);
                },
            ])
            ->add('user', EntityType::class, [
                'label'     => $this->translator->trans('admin.posts.form.label.who_is_the_creator'),
                'class'     => User::class,
                'choice_label' => 'fullname',
                'placeholder' => $this->translator->trans('app.general.form.label.select'). ':',
                'required' => false
            ])
            ->add('published',  DateTimeType::class,[
                'label'     => $this->translator->trans('app.general.published_in'),
            ])
            ->add('template', EntityType::class, [
                'label'     => $this->translator->trans('admin.posts.form.label.which_template'),
                'class'     => Template::class,
                'choice_label' => 'name',
                'placeholder' => $this->translator->trans('app.general.form.label.select'). ':',
                'required' => true
            ])
            ->add('images', HiddenType::class, [
                'mapped' => false,
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
