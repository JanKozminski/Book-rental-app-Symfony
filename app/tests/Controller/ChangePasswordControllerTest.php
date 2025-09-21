<?php

/**
 * ChangePassword controller test.
 */

namespace App\Tests\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Class ChangePasswordControllerTest.
 */
class ChangePasswordControllerTest extends WebTestCase
{

    private KernelBrowser $httpClient;


    /**
     * Set up tests.
     */
    protected function setUp(): void
    {
        $this->httpClient = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
    }

    /**
     * Test change password page is accessible to logged-in user.
     */
    public function testChangePasswordFormAccess(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value], 'test123');
        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', '/change-password');

        // then
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    /**
     * Test submitting change password form.
     */
    public function testChangePasswordSubmit(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value], 'test123');
        $this->httpClient->loginUser($user);

        // when
        $crawler = $this->httpClient->request('GET', '/change-password');

        // then
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');


        $form = $crawler->filter('form')->form([
            'change_password[oldPassword]' => 'test123',
            'change_password[newPassword]' => 'newpass456',
        ]);

        $this->httpClient->submit($form);

        $this->assertResponseRedirects('/');
    }


    /**
     * Helper method to create a user.
     *
     * @param array  $roles    Array of roles to assign to the user
     * @param string $password Plain text password to be hashed
     *
     * @return User Created user
     */
    private function createUser(array $roles, string $password = 'test123'): User
    {
        $user = new User();
        $user->setEmail(uniqid().'@example.com');
        $user->setRoles($roles);

        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $hashedPassword = $hasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        static::getContainer()->get(UserRepository::class)->save($user);

        return $user;
    }
}
