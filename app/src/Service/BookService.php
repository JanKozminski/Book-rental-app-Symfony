<?php
/**
* Book service.
*/

namespace App\Service;



use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;

class BookService{
/**
* Task repository.
*/
    private BookRepository $bookRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;
    /**
     * Author repository.
     */
    private AuthorRepository $authorRepository;

    /**
     * Category repository.
     */
    private CategoryRepository $categoryRepository;


    /**
     * Constructor.
     *
     * @param BookRepository     $bookRepository Book repository
     * @param AuthorRepository $authorRepository Author repository
     * @param CategoryRepository $categoryRepository Category repository
     * @param PaginatorInterface $paginator Paginator
     */
    public function __construct(BookRepository $bookRepository, AuthorRepository $authorRepository, CategoryRepository $categoryRepository, PaginatorInterface $paginator)
    {
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;
        $this->authorRepository =  $authorRepository;
        $this->categoryRepository = $categoryRepository;
    }
}