<?php

namespace App\Form\Type;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;

class BookType extends AbstractType implements FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isbn')
            ->add('title')
            ->add('page_number')
            ->add('release_date', DateType::class, ['widget' => 'single_text'])
            ->add('rating')
            ->add('description')
            ->add('stock')
            ->add('author', EntityType::class, ['class' => Author::class,
                'choice_label' => function ($author): string {
                    return $author->getName();
            }, 'multiple' => true, ])
            ->add('category', EntityType::class, ['class' => Category::class,
                    'choice_label' => function ($category): string {
                        return $category->getName();
            }, 'multiple' => true, ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'book';
    }
}
