<?php
/**
 * Rental service.
 */

namespace App\Service;

use App\Entity\Book;
use App\Entity\Rental;
use App\Repository\RentalRepository;

/**
 * Class RentalService.
 */
class RentalService
{
    /**
     * Rental repository.
     */
    private RentalRepository $rentalRepository;

    /**
     * Constructor.
     *
     * @param RentalRepository $rentalRepository Rental repository
     */
    public function __construct(RentalRepository $rentalRepository)
    {
        $this->rentalRepository = $rentalRepository;
    }

    /**
     * Save entity.
     *
     * @param Rental $rental Rental entity
     */
    public function save(Rental $rental): void
    {
        $this->rentalRepository->save($rental);
    }

    /**
     * Delete entity.
     *
     * @param Rental $rental Rental entity
     */
    public function delete(Rental $rental): void
    {
        $this->rentalRepository->delete($rental);
    }

    /**
     * Find all rentals action.
     *
     * @return array Array of rentals
     */
    public function findAllRentals(): array
    {
        return $this->rentalRepository->findAll();
    }

    /**
     * Find user rentals action.
     *
     * @param int $userId User Id
     *
     * @return array Array of user rentals
     */
    public function findMyRentals(int $userId): array
    {
        return $this->rentalRepository->showRentals($userId);
    }

    /**
     * Check if book is out of stock action.
     *
     * @param Book $value Book entity
     *
     * @return bool Is stock equal to 0 or no
     */
    public function rentable(Book $value): bool
    {
        $stock = $value->getStock();

        if (0 != $stock) {
            return true;
        }

        return false;
    }
}
