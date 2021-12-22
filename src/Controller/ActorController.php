<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Actor;
use App\Entity\Program;

/**
 * @Route("/actor", name="actor_")
 */
Class ActorController extends AbstractController
{
    /**
     * Getting an actor by actorId
     *
     * @Route("/{id<^[0-9]+$>}", name="show)
     * @return Response
     */
    public function show(Actor $actor):Response
    {
        if (!$actor) {
            throw $this->createNotFoundException(
                'Cet acteur n\'existe pas.'
            );
        }

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['actor' => $actor], ['number' => 'ASC']);

        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
            'program' => $program
        ]);
    }
}