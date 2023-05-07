<?php
/**
 * Task controller.
 */

namespace App\Controller;

use App\Entity\Categoryy;
use App\Repository\CategoryyRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController.
 */
#[Route('/category')]
class CategoryyController extends AbstractController
{
    /**
     * Index action.
     *
     * @param TaskRepository $categoryyRepository Task repository
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'category_index',
        methods: 'GET'
    )]
    public function index(CategoryyRepository $categoryyRepository): Response
    {
        $categoryy = $categoryyRepository->findAll();

        return $this->render(
            'category/index.html.twig',
            ['categoriess' => $categoryy]
        );
    }

    /**
     * Show action.
     *
     * @param Categoryy $categoryy Task entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'category_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Categoryy $categoryy): Response
    {
        return $this->render(
            'task/show.html.twig',
            ['categoryy' => $categoryy]
        );
    }
}