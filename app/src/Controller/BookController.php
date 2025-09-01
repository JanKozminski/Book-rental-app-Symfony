<?php

/**
 * Book controller.
 */

namespace App\Controller;

use App\Form\Type\BookType;
use App\Entity\Book;
use App\Service\BookService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class BookController.
 */
#[Route('/')]
class BookController extends AbstractController
{
    /**
     * Book service.
     */
    private BookService $bookService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param BookService         $bookService Book service
     * @param TranslatorInterface $translator  Translator
     */
    public function __construct(BookService $bookService, TranslatorInterface $translator)
    {
        $this->bookService = $bookService;
        $this->translator = $translator;
    }

    /**
     * Home page action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route('/', name: 'book_home_page', methods: ['GET', 'POST'])]
    public function home(Request $request): Response
    {
        $pagination = $this->bookService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        $books = $this->filters($request);

        return $this->render('book/home.html.twig', [
            'books' => $books,
            'author' => $this->bookService->findAllAuthors(),
            'category' => $this->bookService->findAllCategories(),
            'pagination' => $pagination,
        ]);
    }

    /**
     * Manage filters action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    public function filters(Request $request)
    {
        return $this->bookService->getFilters($request);
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route('/book', name: 'book_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request): Response
    {
        return $this->render('book/index.html.twig', [
            'book' => $this->bookService->findAllBooks(),
        ]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/book/new', name: 'book_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->save($book);

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    /**
     * Show action.
     *
     * @param Book $book Book entity
     *
     * @return Response HTTP response
     */
    #[Route('/book/{id}', name: 'book_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Book    $book    Book entity
     *
     * @return Response HTTP response
     */
    #[Route('book/{id}/delete', name: 'book_delete', methods: ['GET|DELETE'])]
    public function delete(Request $request, Book $book): Response
    {
        if (!$this->bookService->canBeDeleted($book)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.book_contains_rental')
            );

            return $this->redirectToRoute('book_index');
        }
        $form = $this->createForm(
            FormType::class,
            $book,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('book_delete', ['id' => $book->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->delete($book);

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/_delete_form.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Book    $book    Book entity
     *
     * @return Response HTTP response
     */
    #[Route('/book/{id}/edit', name: 'book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book): Response
    {
        $form = $this->createForm(
            BookType::class,
            $book
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->save($book);

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }
}
