<?php

/**
 * Rental controller.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Rental;
use App\Form\Type\RentalType;
use App\Service\RentalService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RentalController.
 */
#[Route('/rental')]
#[IsGranted('ROLE_USER')]
class RentalController extends AbstractController
{
    /**
     * Rental service.
     */
    private RentalService $rentalService;

    /**
     * Constructor.
     *
     * @param RentalService $rentalService Rental service
     */
    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }

    /**
     * User rentals index action.
     *
     * @return Response HTTP response
     */
    #[Route('/user', name: 'rental_user', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function private(): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();

        return $this->render('rental/rental.html.twig', [
            'rental' => $this->rentalService->findMyRentals($userId),
        ]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     * @param Book    $book    Book entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'rental_new', methods: ['GET', 'POST'])]
    public function rent(Request $request, Book $book): Response
    {
        if ($this->rentalService->rentable($book)) {
            $rental = new Rental();
            $rental->setUser($this->getUser());
            $rental->setBook($book);
            $book->setStock($book->getStock() - 1);
            $form = $this->createForm(
                RentalType::class,
                $rental,
                [
                    'method' => 'POST',
                    'action' => $this->generateUrl('rental_new', ['id' => $book->getId()]),
                ]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->rentalService->save($rental);

                return $this->redirectToRoute('book_home_page', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('rental/new.html.twig', [
                'rental' => $rental,
                'form' => $form,
            ]);
        } else {
            return $this->render('default/_stock_message.html.twig');
        }
    }

    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route('/rentalmanage/index', name: 'rental_index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('rental/index.html.twig', [
            'rental' => $this->rentalService->findAllRentals(),
        ]);
    }

    /**
     * Show rental action.
     *
     * @param Rental $rental Rental entity
     *
     * @return Response HTTP response
     */
    #[Route('/rentalmanage/index/{id}', name: 'rental_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(Rental $rental): Response
    {
        return $this->render('rental/show.html.twig', [
            'rental' => $rental,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Rental  $rental  Rental entity
     *
     * @return Response HTTP response
     */
    #[Route('/rental/{id}/delete', name: 'rental_delete', methods: ['GET', 'DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Rental $rental): Response
    {
        $form = $this->createForm(
            FormType::class,
            $rental,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('rental_delete', ['id' => $rental->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->rentalService->delete($rental);

            return $this->redirectToRoute('rental_index');
        }

        return $this->render(
            'rental/_delete_form.html.twig',
            [
                'form' => $form->createView(),
                'rental' => $rental,
            ]
        );
    }
}
