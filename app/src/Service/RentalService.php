<?php
/**
 * Rental service.
 */

namespace App\Service;

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
     * @return array
     */
    public function findAllRentals(): array
    {
        return $this->rentalRepository->findAll();
    }

    /**
     * Find user rentals action.
     *
     * @param $userId
     *
     * @return array
     */
    public function findMyRentals($userId): array
    {
        return $this->rentalRepository->showRentals($userId);
    }
}
