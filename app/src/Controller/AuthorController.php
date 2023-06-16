<?php
/**
 * Author controller.
 */

namespace App\Controller;

use App\Form\Type\AuthorType;
use App\Entity\Author;
use App\Service\AuthorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorController.
 */
#[Route('/author')]
#[IsGranted('ROLE_USER')]
class AuthorController extends AbstractController
{
    /**
     * Book service.
     */
    private AuthorService $authorService;

    /**
     * Constructor.
     *
     * @param AuthorService $authorService Author Service
     */
    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route('/', name: 'author_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'author' => $this->authorService->findAllAuthors(),
        ]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/new', name: 'author_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorService->save($author);

            return $this->redirectToRoute('author_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('author/new.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }

    /**
     * Show action.
     *
     * @param Author $author Author entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'author_show', methods: ['GET'])]
    public function show(Author $author): Response
    {
        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Author  $author  Author entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'author_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Author $author): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorService->save($author);

            return $this->redirectToRoute('author_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('author/edit.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Author  $author  Author entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'author_delete', methods: ['GET|DELETE'])]
    public function delete(Request $request, Author $author): Response
    {
        $form = $this->createForm(
            FormType::class,
            $author,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('author_delete', ['id' => $author->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorService->delete($author);

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/_delete_form.html.twig',
            [
                'form' => $form->createView(),
                'author' => $author,
            ]
        );
    }
}
