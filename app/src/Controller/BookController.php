<?php

namespace App\Controller;

use App\Form\Type\BookType;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\CategoryRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class BookController extends AbstractController
{
    #[Route('/', name: 'book_home_page', methods: ['GET', 'POST'])]
    public function home(Request $request, BookRepository $bookRepository, CategoryRepository $categoryRepository, AuthorRepository $authorRepository): Response
    {
        $books = $bookRepository->findAll();
        if ($request->isMethod('GET')) {
            $books = $this->filters($request, $bookRepository, $categoryRepository, $authorRepository);
        }

        return $this->render('book/home.html.twig', [
            'books' => $books,
            'author' => $authorRepository->findAll(),
            'category' => $categoryRepository->findAll(),
        ]);
    }

    private function filters(Request $request, BookRepository $bookRepository, CategoryRepository $categoryRepository, AuthorRepository $authorRepository)
    {
        if ($request->get('keywords')) {
            return $bookRepository->findByTitleField($request->query->get('keywords'));
        } elseif ($request->query->get('rating')) {
            return $bookRepository->findByRatingField($request->query->get('rating'));
        } elseif ($request->query->get('date1') && $request->query->get('date2')) {
            return $bookRepository->findByDateField($request->query->get('date1'), $request->query->get('date2'));
        } elseif ($request->query->get('author')) {
            $id = $request->query->get('author');
            $author = $authorRepository->find($id);

            return $author->getBooks();
        } elseif ($request->query->get('category')) {
            $id = $request->query->get('category');
            $category = $categoryRepository->find($id);

            return $category->getBooks();
        } else {
            return $bookRepository->findAll();
        }
    }

    #[Route('/book', name: 'book_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'book' => $bookRepository->findAll(),
        ]);
    }
    #[Route('/book/new', name: 'book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/book/{id}', name: 'book_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/book/{id}/delete', name: 'book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/book/{id}/edit', name: 'book_edit', methods: ['GET', 'POST'])]
    //#[ParamConverter('id', class: 'Book', options:['id'=>'id'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookType::class, $book,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('book_edit', ['id' => $book->getId()]),
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }
}
