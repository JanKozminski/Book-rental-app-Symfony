<?php

/**
 * Author controller test.
 */

namespace App\Tests\Controller;

use App\Entity\Author;
use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class AuthorControllerTest.
 */
class AuthorControllerTest extends WebTestCase
{
    /**
     * Default test route.
     */
    public const TEST_ROUTE = '/author';

    /**
     * Create route.
     */
    public const TEST_ROUTE_CREATE = '/author/new';

    private KernelBrowser $httpClient;

    /**
     * Set up test client.
     */
    protected function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test index route as anonymous user (should redirect).
     */
    public function testIndexRouteAsAnonymous(): void
    {
        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);

        // then
        $this->assertResponseRedirects();
    }

    /**
     * Test index route as authenticated user.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface
     */
    public function testIndexRouteAsAuthenticated(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/');

        // then
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table'); // assuming you list authors in a table
    }

    /**
     * Test create route as authenticated user (GET).
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface
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
     * Create test user.
     *
     * @param array $roles Array of user roles
     *
     * @return User The created User entity
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createUser(array $roles): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setEmail(uniqid().'@example.com');
        $user->setRoles($roles);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                'p@ssword'
            )
        );

        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }

    /**
     * Create author helper.
     *
     * @return Author The created Author entity
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createAuthor(): Author
    {
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $author = new Author();
        $author->setName('Existing Author');
        $author->setSex('F');
        $author->setBirthDate(new DateTimeImmutable('1990-04-20'));

        $entityManager->persist($author);
        $entityManager->flush();

        return $author;
    }
}
