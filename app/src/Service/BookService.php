<?php
/**
 * Book service.
 */

namespace App\Service;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\RentalRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BookService.
 */
class BookService
{
    /**
     * Book repository.
     */
    private BookRepository $bookRepository;

    /**
     * Category repository.
     */
    private CategoryRepository $categoryRepository;

    /**
     * Author repository.
     */
    private AuthorRepository $authorRepository;

    /**
     * Rental repository.
     */
    private RentalRepository $rentalRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param BookRepository     $bookRepository     Book repository
     * @param PaginatorInterface $paginator          Paginator
     * @param CategoryRepository $categoryRepository Category repository
     * @param AuthorRepository   $authorRepository   Author repository
     * @param RentalRepository   $rentalRepository   Rental repository
     */
    public function __construct(BookRepository $bookRepository, PaginatorInterface $paginator, CategoryRepository $categoryRepository, AuthorRepository $authorRepository, RentalRepository $rentalRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;
        $this->categoryRepository = $categoryRepository;
        $this->authorRepository = $authorRepository;
        $this->rentalRepository = $rentalRepository;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->bookRepository->findAll(),
            $page,
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Book $book Book entity
     */
    public function save(Book $book): void
    {
        $this->bookRepository->save($book);
    }

    /**
     * Delete entity.
     *
     * @param Book $book Book entity
     */
    public function delete(Book $book): void
    {
        $this->bookRepository->delete($book);
    }

    /**
     * Can Category be deleted?
     *
     * @param Book $book Book entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Book $book): bool
    {
        try {
            $result = $this->rentalRepository->countByBook($book);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }// end canBeDeleted()

    /**
     * Find all books action.
     *
     * @return array
     */
    public function findAllBooks()
    {
        return $this->bookRepository->findAll();
    }

    /**
     * Find all authors action.
     *
     * @return array
     */
    public function findAllAuthors()
    {
        return $this->authorRepository->findAll();
    }

    /**
     * Find all categories action.
     *
     * @return array
     */
    public function findAllCategories()
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * Manage Filters action.
     *
     * @param Request $request Request taken from a user
     *
     * @return Book|Collection|float|int|mixed|string
     */
    public function getFilters(Request $request): mixed
    {
        if ($request->get('keywords')) {
            return $this->bookRepository->findByTitleField($request->query->get('keywords'));
        } elseif ($request->query->get('rating')) {
            return $this->bookRepository->findByRatingField($request->query->get('rating'));
        } elseif ($request->query->get('date1') && $request->query->get('date2')) {
            return $this->bookRepository->findByDateField($request->query->get('date1'), $request->query->get('date2'));
        } elseif ($request->query->get('author')) {
            $id = $request->query->get('author');
            $author = $this->authorRepository->find($id);

            return $author->getBooks();
        } elseif ($request->query->get('category')) {
            $id = $request->query->get('category');
            $category = $this->categoryRepository->find($id);

            return $category->getBooks();
        } else {
            return $this->bookRepository->findAll();
        }
    }
}
