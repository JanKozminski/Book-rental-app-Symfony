<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\CategoryRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        } else if ($request->query->get('rating')) {
            return $bookRepository->findByRatingField($request->query->get('rating'));
        } else if ($request->query->get('date1') && $request->query->get('date2')) {
            return $bookRepository->findByDateField($request->query->get('date1'), $request->query->get('date2'));
        } else if ($request->query->get('author')) {
            $id = $request->query->get("author");
            $author = $authorRepository->find($id);
            return $author->getBooks();
        } else if ($request->query->get('category')) {
            $id = $request->query->get("category");
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
    #[Route('/book/{id}', name: 'book_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}
