<?php

/**
 * Book controller test.
 */

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class BookControllerTest.
 */
class BookControllerTest extends WebTestCase
{
    /**
     * Default test route.
     */
    public const TEST_ROUTE = '/book';

    /**
     * Create test route.
     */
    public const TEST_ROUTE_CREATE = '/book/new';

    /**
     * Test client.
     */
    private KernelBrowser $httpClient;

    /**
     * Entity manager.
     */
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
     * Test index route as anonymous user.
     */
    public function testIndexAnonymousRedirects(): void
    {
        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);

        // then
        $this->assertResponseStatusCodeSame(302);
    }

    /**
     * Test index route as authenticated user.
     */
    public function testIndexRouteAsAuthenticated(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);

        // then
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table'); // assuming books are listed in a table
    }

    /**
     * Test create form access for authenticated user.
     */
    public function testCreateFormAccess(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE_CREATE);

        // then
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    /**
     * Test show route for book.
     */
    public function testShowBook(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);
        $book = $this->createBook();

        // when
        $this->httpClient->request('GET', '/book/'.$book->getId());

        // then
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1'); // assuming title is in h1
    }

    /**
     * Test edit book form.
     */
    public function testEditBook(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);
        $book = $this->createBook();

        // when
        $this->httpClient->request('GET', '/book/'.$book->getId().'/edit');

        // then
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    /**
     * Test delete book via GET/DELETE route.
     */
    public function testDeleteBook(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);
        $book = $this->createBook();

        // when
        $this->httpClient->request('GET', '/book/'.$book->getId().'/delete');

        // then
        $this->assertResponseIsSuccessful();
    }

    /**
     * Create fake user.
     *
     * @param array $roles Roles to assign
     *
     * @return User
     */
    private function createUser(array $roles): User
    {
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $user = new User();
        $user->setEmail(uniqid().'@test.com');
        $user->setRoles($roles);
        $user->setPassword($passwordHasher->hashPassword($user, 'test123'));

        static::getContainer()->get(UserRepository::class)->save($user);

        return $user;
    }

    /**
     * Create test book entity.
     *
     * @return Book
     */
    private function createBook(): Book
    {
        $book = new Book();
        $book->setTitle('Test Book');
        $book->setIsbn('1234567890123');
        $book->setStock(5);
        $book->setRating(4);
        $book->setPageNumber(250);
        $book->setDescription('Sample description.');
        $book->setReleaseDate(new \DateTimeImmutable('2023-01-01'));

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }
}
