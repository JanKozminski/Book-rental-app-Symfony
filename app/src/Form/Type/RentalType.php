<?php

namespace App\Form\Type;

use App\Entity\Book;
use App\Entity\Rental;
use App\Entity\User;
use App\Repository\BookRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;;

class RentalType extends AbstractType implements FormTypeInterface
{

    private $security;

    private $router;

    public function __construct(RouterInterface $router, Security $security, )
    {
        $this->router = $router;
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $userId = $this->security->getUser()->getId();
       $idFromUrl = $options['id_from_url'];

        $builder
            ->add('user',EntityType::class, [
                'class' => User::class,
                'choice_value' => $userId ])
            ->add('book', EntityType::class, [
                'class' => Book::class,
                'choice_label' => function () use ($idFromUrl): int {
                    return $idFromUrl;
                }
            ])
            ->add('email')
            ->add('comment');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
        ]);
        $resolver->setRequired('id_from_url');
    }

}
