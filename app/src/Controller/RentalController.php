<?php

namespace App\Controller;

use App\Entity\Rental;
use App\Form\Type\RentalType;
use App\Repository\RentalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rental')]
#[IsGranted('ROLE_USER')]
class RentalController extends AbstractController
{
    #[Route('/', name: 'rental_index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(RentalRepository $rentalRepository): Response{
        return $this->render('rental/index.html.twig', [
            'rental' => $rentalRepository->findAll(),
        ]);
    }
    #[Route('/{id}', name: 'rental_new', methods: ['GET', 'POST'])]
    public function rent(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $rental = new Rental();
        $form = $this->createForm(RentalType::class, $rental,
            [
                'id_from_url' => $id,
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rental);
            $entityManager->flush();

            return $this->redirectToRoute('book_home_page', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rental/new.html.twig', [
            'rental' => $rental,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'rental_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Rental $rental): Response
    {
        return $this->render('rental/show.html.twig', [
            'rental' => $rental,
        ]);
    }

    #[Route('/rental/{id}/delete', name: 'rental_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Rental $rental, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rental->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rental);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rental_index', [], Response::HTTP_SEE_OTHER);
    }
}