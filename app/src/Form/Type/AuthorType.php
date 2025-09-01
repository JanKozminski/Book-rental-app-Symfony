<?php

/**
 * Author type.
 */

namespace App\Form\Type;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * Class AuthorType.
 */
class AuthorType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'author.author',
                ]
            )
            ->add(
                'sex',
                TextType::class,
                [
                    'label' => 'author.sex',
                ]
            )
            ->add(
                'birthDate',
                DateType::class,
                ['widget' => 'single_text',
                    'label' => 'author.date',
                ]
            )
            ->add(
                'countryOfOrigin',
                TextType::class,
                [
                    'label' => 'author.country',
                ]
            )
            ->add(
                'books',
                EntityType::class,
                ['class' => Book::class,
                    'choice_label' => function ($book): string {
                        return $book->getTitle();
                    },
                    'multiple' => true, ]
            )
        ;
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'author';
    }
}
