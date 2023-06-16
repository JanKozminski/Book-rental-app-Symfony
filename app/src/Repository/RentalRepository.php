<?php
/**
 * Rental repository.
 */

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Rental;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Rental>
 *
 * @method Rental|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rental|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rental[]    findAll()
 * @method Rental[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentalRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rental::class);
    }

    /**
     * Save entity.
     *
     * @param Rental $rental Rental entity
     */
    public function save(Rental $rental): void
    {
        $this->_em->persist($rental);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Rental $rental Rental entity
     */
    public function delete(Rental $rental): void
    {
        $this->_em->remove($rental);
        $this->_em->flush();
    }

    /**
     * Show rentals for user action.
     *
     * @param int $userId User Id
     *
     * @return mixed Result
     */
    public function showRentals(int $userId): mixed
    {
        return $this->createQueryBuilder('e')
            ->where('e.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Count tasks by category.
     *
     * @param Book $book Book
     *
     * @return int Number of rentals in book
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByBook(Book $book): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('rental.id'))
            ->where('rental.book = :book')
            ->setParameter(':book', $book)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('rental');
    }

//    /**
//     * @return Rental[] Returns an array of Rental objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Rental
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
