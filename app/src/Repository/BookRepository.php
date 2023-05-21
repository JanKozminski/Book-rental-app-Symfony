<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByTitleField($value)
    {
        /*
        return $this->createQueryBuilder('t')
            ->Where('t.book LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
        */

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT t
            FROM App\Entity\Book t
            WHERE t.title LIKE :val
            ORDER BY t.release_date ASC"
        )->setParameter('val', "%".$value."%");

        // returns an array of Product objects
        return $query->getResult();

    }

    public function findByRatingField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.rating = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByDateField($date1, $date2) //takes date
    {
        return $this->createQueryBuilder('t')
            ->where('t.release_date >= :date1')
            ->andWhere('t.release_date <= :date2')
            ->setParameter('date1', $date1)
            ->setParameter('date2', $date2)
            ->orderBy('t.release_date', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }


//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('k.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
