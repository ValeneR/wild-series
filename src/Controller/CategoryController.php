<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;

/**
 * @Route("/category", name="category_")
 */
Class CategoryController extends AbstractController
{
    /**
     * Show all rows from Category’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'category/index.html.twig',
            ['categories' => $categories]
        );
    }

    /**
     * Getting all programs in a category
     *
     * @Route("/{categoryName}", name="show")
     * @return Response
     */
    public function show(string $categoryName):Response
    {
        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException(
                'Cette catégorie n\'existe pas.'
            );
        }
        else {
            $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category->getId()],
            ['id' => 'ASC'],3);
        }
        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'name' => $categoryName,
        ]);
    }

    /**
     * The controller for the category add form
     *
     * @Route("/new", name="new", priority="1")
     */
    public function new(Request $request) : Response
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($category);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to categories list
            return $this->redirectToRoute('category_index');
        }
        // Render the form
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
