<?php
namespace App\Service;


use App\Entity\Categoryy;

/**
 * Save entity.
 *
 * @param Categoryy $category Category entity
 */
interface CategoryyServiceInterface
{
    public function save(Categoryy $category): void;

    public function delete(Categoryy $category): void;
}