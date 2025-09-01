<?php

/**
 * UserService test.
 */

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\UserService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class UserServiceTest.
 */
class UserServiceTest extends KernelTestCase
{
    /**
     * Entity manager.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * User service.
     */
    private ?UserService $userService;

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
        $this->userService = $container->get(UserService::class);
    }

    /**
     * Test save().
     */
    public function testSave(): void
    {
        // given
        $user = new User();
        $user->setEmail(uniqid().'@example.com');
        $user->setPassword('TestPassword123!');
        $user->setRoles(['ROLE_USER']);

        // when
        $this->userService->save($user);

        // then
        $savedUser = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.id = :id')
            ->setParameter(':id', $user->getId(), Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($user->getEmail(), $savedUser->getEmail());
        $this->assertEquals($user->getRoles(), $savedUser->getRoles());
    }
}
