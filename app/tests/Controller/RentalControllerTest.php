<?php

/**
 * Rental entity test class.
 */

namespace App\Tests\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Entity\Book;
use App\Entity\Rental;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class RentalControllerTest extends WebTestCase
{
    private KernelBrowser $httpClient;
    private EntityManagerInterface $entityManager;

    /**
     * Set up tests.
     */
    protected function setUp(): void
    {
        $this->httpClient = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
    }

    /**
     * Tests access to user's personal rentals (route: /rental/user).
     */
    public function testPrivateRentalsAccess(): void
    {
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);

        $this->httpClient->request('GET', '/rental/user');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }

    /**
     * Tests admin access to the rental management index page (route: /rental/rentalmanage/index).
     */
    public function testRentalIndexAsAdmin(): void
    {
        $admin = $this->createUser([UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($admin);

        $this->httpClient->request('GET', '/rental/rentalmanage/index');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }

    /**
     * Tests showing a single rental as an admin user (route: /rental/rentalmanage/index/{id}).
     */
    public function testShowRentalAsAdmin(): void
    {
        $admin = $this->createUser([UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($admin);
        $rental = $this->createRental();

        $this->httpClient->request('GET', '/rental/rentalmanage/index/'.$rental->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    /**
     * Helper method to create a test user.
     *
     * @param array  $roles    Roles to assign to the user
     * @param string $password Plaintext password to be hashed
     *
     * @return User Created user instance
     */
    private function createUser(array $roles, string $password = 'test123'): User
    {
        $user = new User();
        $user->setEmail(uniqid().'@example.com');
        $user->setRoles($roles);

        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, $password));

        static::getContainer()->get(UserRepository::class)->save($user);

        return $user;
    }

    /**
     * Helper method to create a test rental with an associated book.
     *
     * @return Rental Created rental instance
     */
    private function createRental(): Rental
    {
        $book = new Book();
        $book->setTitle('Test Book');
        $book->setStock(1);

        $user = $this->createUser([UserRole::ROLE_USER->value]);

        $rental = new Rental();
        $rental->setBook($book);
        $rental->setUser($user);
        $rental->setEmail($user->getEmail());
        $rental->setComment('Test comment');

        $this->entityManager->persist($book);
        $this->entityManager->persist($rental);
        $this->entityManager->flush();

        return $rental;
    }
}
