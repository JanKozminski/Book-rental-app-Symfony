<?php
namespace App\Service;

use App\Entity\Categoryy;
use App\Repository\CategoryyRepository;

class CategoryyService implements CategoryyServiceInterface{

private CategoryyRepository $categoryyRepository;

    public function __construct(CategoryyRepository $categoryRepository){
    $this->categoryyRepository = $categoryRepository;
    }


  public function save(Categoryy $category): void
{
    if (null == $category->getId()) {
        $category->setCreatedAt(new \DateTimeImmutable());
    }
    $category->setUpdatedAt(new \DateTimeImmutable());

    $this->categoryyRepository->save($category);
}
    /**
     * Delete entity.
     *
     * @param Categoryy $category Category entity
     */
    public function delete(Categoryy $category): void
    {
        $this->_em->remove($category);
        $this->_em->flush();
    }
}
