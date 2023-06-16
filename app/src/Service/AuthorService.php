<?php
/**
 * Author service.
 */

namespace App\Service;

use App\Entity\Author;
use App\Repository\AuthorRepository;

/**
 * Class AuthorService.
 */
class AuthorService
{
    /**
     * Author repository.
     */
    private AuthorRepository $authorRepository;

    /**
     * Constructor.
     *
     * @param AuthorRepository $authorRepository Author repository
     */
    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * Find all authors action.
     *
     * @return array Array of authors
     */
    public function findAllAuthors(): array
    {
        return $this->authorRepository->findAll();
    }

    /**
     * Save entity.
     *
     * @param Author $author Author entity
     */
    public function save(Author $author): void
    {
        $this->authorRepository->save($author);
    }

    /**
     * Delete entity.
     *
     * @param Author $author Author entity
     */
    public function delete(Author $author): void
    {
        $this->authorRepository->delete($author);
    }
}
