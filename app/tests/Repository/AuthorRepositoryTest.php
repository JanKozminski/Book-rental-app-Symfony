<?php

/**
 * Author repository test.
 */

namespace App\Tests\Repository;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AuthorRepositoryTest.
 */
class AuthorRepositoryTest extends KernelTestCase
{
    /**
     * Entity manager.
     */
    private ?EntityManagerInterface $entityManager = null;

    /**
     * Author repository.
     */
    private ?AuthorRepository $repository = null;

    /**
     * Set up tests.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $container->get('doctrine')->getManager();
        $this->repository = $container->get(AuthorRepository::class);
    }

    /**
     * Test saving an author.
     */
    public function testSaveAuthor(): void
    {
        // given
        $author = new Author();
        $author->setName('Jane Doe');
        $author->setSex('F');
        $author->setBirthDate(new DateTimeImmutable('1990-04-20'));
        $author->setCountryOfOrigin('Poland');

        // when
        $this->repository->save($author);

        // then
        $savedAuthor = $this->repository->find($author->getId());
        $this->assertNotNull($savedAuthor);
        $this->assertEquals('John Doe', $savedAuthor->getName());
    }

    /**
     * Test deleting an author.
     */
    public function testDeleteAuthor(): void
    {
        // given
        $author = new Author();
        $author->setName('Jane Doe');
        $author->setSex('F');
        $author->setBirthDate(new DateTimeImmutable('1990-04-20'));
        $author->setCountryOfOrigin('Poland');
        $this->repository->save($author);
        $id = $author->getId();

        // when
        $this->repository->delete($author);

        // then
        $deletedAuthor = $this->repository->find($id);
        $this->assertNull($deletedAuthor);
    }

    /**
     * Test findAll returns an array.
     */
    public function testFindAllReturnsArray(): void
    {
        // when
        $allAuthors = $this->repository->findAll();

        // then
        $this->assertIsArray($allAuthors);
    }

    /**
     * Tear down after tests.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager?->close();
        $this->entityManager = null;
    }
}
