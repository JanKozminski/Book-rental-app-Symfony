<?php
/**
 * Task controller.
 */

namespace App\Controller;

use App\Entity\Categoryy;
use App\Form\Type\CategoryType;
use App\Repository\CategoryyRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CategoryyServiceInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TaskController.
 */
#[Route('/categoryy')]
class CategoryyController extends AbstractController
{

    private CategoryyServiceInterface $categoryService;

    private TranslatorInterface $translator;
    public function __construct(CategoryyServiceInterface $categoryyService, TranslatorInterface $translator){
        $this->categoryService = $categoryyService;
        $this->translator = $translator;
    }
    /**
     * Index action.
     *
     * @param CategoryyRepository $categoryyRepository Categoryy repository
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
            'categoryy/index.html.twig',
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
            'categoryy/show.html.twig',
            ['categoryy' => $categoryy]
        );
    }

    #[Route(
        '/create',
        name: 'category_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $category = new Categoryy();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'categoryy/create.html.twig',
            ['form' => $form->createView()]
        );
    }
    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param Categoryy $category Category entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'category_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Categoryy $category): Response
    {
        $form = $this->createForm(
            CategoryType::class,
            $category,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('category_edit', ['id' => $category->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'categoryy/edit.html.twig',
            [
                'form' => $form->createView(),
                'categoryy' => $category,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Categoryy $category Category entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'category_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Categoryy $category): Response
    {
        $form = $this->createForm(FormType::class, $category, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('category_delete', ['id' => $category->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->delete($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'categoryy/delete.html.twig',
            [
                'form' => $form->createView(),
                'categoryy' => $category,
            ]
        );
    }
}