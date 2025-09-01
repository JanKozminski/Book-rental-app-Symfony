<?php

/**
 * Book service test.
 */

namespace App\Tests\Service;

use App\Entity\Book;
use App\Service\BookService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class BookServiceTest.
 */
class BookServiceTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?BookService $bookService;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->bookService = $container->get(BookService::class);
    }

    /**
     * Test save().
     *
     * @throws ORMException
     */
    public function testSave(): void
    {
        // given
        $book = new Book();
        $book->setTitle('New Book');
        $book->setIsbn('1234567890123');
        $book->setRating(4);
        $book->setPageNumber(300);
        $book->setDescription('Interesting description');
        $book->setStock(5);
        $book->setReleaseDate(new \DateTimeImmutable('2022-01-01'));

        // when
        $this->bookService->save($book);
        $bookId = $book->getId();

        // then
        $result = $this->entityManager->createQueryBuilder()
            ->select('b')
            ->from(Book::class, 'b')
            ->where('b.id = :id')
            ->setParameter(':id', $bookId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($book, $result);
    }

    /**
     * Test delete().
     *
     * @throws ORMException
     */
    public function testDelete(): void
    {
        // given
        $book = new Book();
        $book->setTitle('Book to delete');
        $book->setIsbn('1234567890123');
        $book->setRating(4);
        $book->setPageNumber(200);
        $book->setDescription('To be removed');
        $book->setStock(2);
        $book->setReleaseDate(new \DateTimeImmutable('2020-06-15'));

        $this->entityManager->persist($book);
        $this->entityManager->flush();
        $bookId = $book->getId();

        // when
        $this->bookService->delete($book);

        // then
        $result = $this->entityManager->createQueryBuilder()
            ->select('b')
            ->from(Book::class, 'b')
            ->where('b.id = :id')
            ->setParameter(':id', $bookId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($result);
    }
}
