<?php

/**
 * Category controller test.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class CategoryControllerTest.
 */
class CategoryControllerTest extends WebTestCase
{
    public const TEST_ROUTE = '/category';
    public const TEST_ROUTE_CREATE = '/category/new';

    private KernelBrowser $httpClient;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->httpClient = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
    }

    /**
     * Test index route as anonymous user.
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
     */
    public function testIndexRouteAsAuthenticated(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE. '/');

        // then
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }

    /**
     * Test category create form access.
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
     * Test show category page.
     */
    public function testShowCategory(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);
        $category = $this->createCategory();

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$category->getId());

        // then
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    /**
     * Test edit category form.
     */
    public function testEditCategory(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);
        $category = $this->createCategory();

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$category->getId().'/edit');

        // then
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    /**
     * Test delete category via GET/DELETE.
     */
    public function testDeleteCategory(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->httpClient->loginUser($user);
        $category = $this->createCategory();

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$category->getId().'/delete');

        // then
        $this->assertResponseIsSuccessful();
    }

    /**
     * Create fake user.
     */
    private function createUser(array $roles): User
    {
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $user = new User();
        $user->setEmail(uniqid().'@category.test');
        $user->setRoles($roles);
        $user->setPassword($hasher->hashPassword($user, 'test123'));

        static::getContainer()->get(UserRepository::class)->save($user);

        return $user;
    }

    /**
     * Create test category.
     */
    private function createCategory(): Category
    {
        $category = new Category();
        $category->setName('Test Category');

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }
}
