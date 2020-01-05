# Form
#### Relationship
For insert **OneToMany** or **ManyToMany** use `EntityType::class`, follow an example:
```php
$builder
    ->add('categories', EntityType::class, [
        'label'     => 'Choose the categories',
        'class'     => Category::class,
        'choice_label' => 'name',
        'expanded'  => true,
        'multiple'  => true
    ])
```
For more info, click [here](https://symfony.com/doc/current/reference/forms/types/entity.html)