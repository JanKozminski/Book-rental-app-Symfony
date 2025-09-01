<?php

/**
 * Category service test.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Service\CategoryService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CategoryServiceTest.
 */
class CategoryServiceTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?CategoryService $categoryService;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->categoryService = $container->get(CategoryService::class);
    }

    /**
     * Test findAllCategories().
     */
    public function testFindAllCategoriesReturnsAllCategories(): void
    {
        // given
        $category = new Category();
        $category->setName('Science Fiction');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        // when
        $result = $this->categoryService->findAllCategories();

        // then
        $this->assertNotEmpty($result);
        $this->assertContains($category, $result);
    }

    /**
     * Test save().
     *
     * @throws ORMException
     */
    public function testSave(): void
    {
        // given
        $category = new Category();
        $category->setName('Test Category');

        // when
        $this->categoryService->save($category);
        $categoryId = $category->getId();

        // then
        $result = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c')
            ->where('c.id = :id')
            ->setParameter(':id', $categoryId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($category, $result);
    }

    /**
     * Test delete().
     *
     * @throws ORMException
     */
    public function testDelete(): void
    {
        // given
        $category = new Category();
        $category->setName('To be deleted');

        $this->entityManager->persist($category);
        $this->entityManager->flush();
        $categoryId = $category->getId();

        // when
        $this->categoryService->delete($category);

        // then
        $result = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c')
            ->where('c.id = :id')
            ->setParameter(':id', $categoryId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($result);
    }
}
