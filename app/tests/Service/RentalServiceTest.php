<?php

/**
 * Rental service test.
 */

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\Rental;
use App\Entity\User;
use App\Service\RentalService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class RentalServiceTest.
 */
class RentalServiceTest extends KernelTestCase
{
    /**
     * Entity manager.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Rental service.
     */
    private ?RentalService $rentalService;

    /**
     * Set up test.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->rentalService = $container->get(RentalService::class);
    }

    /**
     * Test save().
     *
     * @throws ORMException
     */
    public function testSave(): void
    {
        // given
        $user = $this->createUser();
        $book = $this->createBook('Test Book A', 2);
        $expectedRental = new Rental();
        $expectedRental->setUser($user);
        $expectedRental->setBook($book);
        $expectedRental->setEmail('meghantorres@yahoo.com');
        $expectedRental->setComment('While indeed affect fast seem.');

        // when
        $this->rentalService->save($expectedRental);

        // then
        $expectedId = $expectedRental->getId();
        $result = $this->entityManager->createQueryBuilder()
            ->select('rental')
            ->from(Rental::class, 'rental')
            ->where('rental.id = :id')
            ->setParameter(':id', $expectedId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedRental, $result);
    }

    /**
     * Test delete().
     *
     * @throws ORMException
     */
    public function testDelete(): void
    {
        // given
        $user = $this->createUser();
        $book = $this->createBook('Test Book B', 1);
        $rental = new Rental();
        $rental->setUser($user);
        $rental->setBook($book);
        $rental->setEmail('meghantorres@yahoo.com');
        $rental->setComment('While indeed affect fast seem.');
        $this->entityManager->persist($rental);
        $this->entityManager->flush();
        $deletedId = $rental->getId();

        // when
        $this->rentalService->delete($rental);

        // then
        $result = $this->entityManager->createQueryBuilder()
            ->select('rental')
            ->from(Rental::class, 'rental')
            ->where('rental.id = :id')
            ->setParameter(':id', $deletedId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($result);
    }

    /**
     * Test findAllRentals().
     */
    public function testFindAllRentalsReturnsAllRentals(): void
    {
        // given
        $user = $this->createUser();
        $book = $this->createBook('Book for All Rentals', 1);

        $rental = new Rental();
        $rental->setUser($user);
        $rental->setBook($book);
        $rental->setEmail('user@example.com');
        $rental->setComment('Test rental');
        $this->entityManager->persist($rental);
        $this->entityManager->flush();

        // when
        $result = $this->rentalService->findAllRentals();

        // then
        $this->assertNotEmpty($result);
        $this->assertContains($rental, $result);
    }


    /**
     * Test findMyRentals().
     */
    public function testFindMyRentalsReturnsOnlyUserRentals(): void
    {
        // given
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $book = $this->createBook('Book', 2);

        $rental1 = new Rental();
        $rental1->setUser($user1);
        $rental1->setBook($book);
        $rental1->setEmail('user1@example.com');
        $rental1->setComment('User1 rental');
        $this->entityManager->persist($rental1);

        $rental2 = new Rental();
        $rental2->setUser($user2);
        $rental2->setBook($book);
        $rental2->setEmail('user2@example.com');
        $rental2->setComment('User2 rental');
        $this->entityManager->persist($rental2);

        $this->entityManager->flush();

        // when
        $result = $this->rentalService->findMyRentals($user1->getId());

        // then
        $this->assertNotEmpty($result);
        $this->assertContains($rental1, $result);
        $this->assertNotContains($rental2, $result);
    }

    /**
     * Test rentable() returns false when stock is 0.
     */
    public function testRentableReturnsFalseIfStockIsZero(): void
    {
        // given
        $book = new Book();
        $book->setTitle('No Stock Book');
        $book->setStock(0);
        $book->setIsbn('1234567890123');
        $book->setRating(5);
        $book->setPageNumber(130);
        $book->setDescription("Super book");
        $book->setReleaseDate(new \DateTimeImmutable('2023-10-15'));

        // when
        $result = $this->rentalService->rentable($book);

        // then
        $this->assertFalse($result);
    }

    /**
     * Test rentable() returns true when stock is available.
     */
    public function testRentableReturnsTrueIfStockIsAvailable(): void
    {
        // given
        $book = new Book();
        $book->setTitle('Available Book');
        $book->setStock(3);
        $book->setIsbn('1234567890123');
        $book->setRating(5);
        $book->setPageNumber(130);
        $book->setDescription("Super book");
        $book->setReleaseDate(new \DateTimeImmutable('2023-10-15'));

        // when
        $result = $this->rentalService->rentable($book);

        // then
        $this->assertTrue($result);
    }

    // --- Pomocnicze metody ---

    /**
     * Create user entity.
     *
     * @return User Created user
     */
    private function createUser(): User
    {
        $user = new User();
        $user->setEmail(uniqid().'@example.com');
        $user->setPassword('p@ssword');
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * Create book entity.
     *
     * @param string $title Book title
     * @param int    $stock Book stock
     *
     * @return Book Created book
     */
    private function createBook(string $title, int $stock): Book
    {
        $book = new Book();
        $book->setTitle($title);
        $book->setStock($stock);
        $book->setIsbn('1234567890123');
        $book->setRating(5);
        $book->setPageNumber(130);
        $book->setDescription("Super book");
        $book->setReleaseDate(new \DateTimeImmutable('2023-10-15'));

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }
}
