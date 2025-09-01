<?php

/**
 * Author service test.
 */

namespace App\Tests\Service;

use App\Entity\Author;
use App\Service\AuthorService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class AuthorServiceTest.
 */
class AuthorServiceTest extends KernelTestCase
{
    /**
     * Entity manager.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Author service.
     */
    private ?AuthorService $authorService;

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
        $this->authorService = $container->get(AuthorService::class);
    }

    /**
     * Test findAllAuthors().
     */
    public function testFindAllAuthorsReturnsAllAuthors(): void
    {
        // given
        $author = new Author();
        $author->setName('Jane Doe');
        $author->setSex('F');
        $author->setBirthDate(new \DateTimeImmutable('1975-06-15'));
        $author->setCountryOfOrigin('PL');
        $this->entityManager->persist($author);
        $this->entityManager->flush();

        // when
        $result = $this->authorService->findAllAuthors();

        // then
        $this->assertNotEmpty($result);
        $this->assertContains($author, $result);
    }

    /**
     * Test save().
     *
     * @throws ORMException
     */
    public function testSave(): void
    {
        // given
        $expectedAuthor = new Author();
        $expectedAuthor->setName('John Doe');
        $expectedAuthor->setSex('M');
        $expectedAuthor->setBirthDate(new \DateTimeImmutable('1980-01-01'));
        $expectedAuthor->setCountryOfOrigin('US');

        // when
        $this->authorService->save($expectedAuthor);

        // then
        $expectedId = $expectedAuthor->getId();
        $result = $this->entityManager->createQueryBuilder()
            ->select('author')
            ->from(Author::class, 'author')
            ->where('author.id = :id')
            ->setParameter(':id', $expectedId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedAuthor, $result);
    }

    /**
     * Test delete().
     *
     * @throws ORMException
     */
    public function testDelete(): void
    {
        // given
        $author = new Author();
        $author->setName('Jane Doe');
        $author->setSex('F');
        $author->setBirthDate(new \DateTimeImmutable('1975-05-10'));
        $author->setCountryOfOrigin('GB');

        $this->entityManager->persist($author);
        $this->entityManager->flush();
        $deletedId = $author->getId();

        // when
        $this->authorService->delete($author);

        // then
        $result = $this->entityManager->createQueryBuilder()
            ->select('author')
            ->from(Author::class, 'author')
            ->where('author.id = :id')
            ->setParameter(':id', $deletedId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($result);
    }
}
