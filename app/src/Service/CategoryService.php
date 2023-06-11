<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

/**
 * Class CategoryService.
 */
class CategoryService
{
    /**
     * Category repository.
     */
    private CategoryRepository $categoryRepository;

    /**
     * Constructor.
     *
     * @param CategoryRepository $categoryRepository Category repository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Find all categories action.
     *
     * @return array
     */
    public function findAllCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Delete entity.
     *
     * @param Category $author Author
     */
    public function delete(Category $author): void
    {
        $this->categoryRepository->delete($author);
    }
}
