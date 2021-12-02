<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;

/**
 * @Route("/program", name="program_")
 */
Class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * Getting a program by programId
     *
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     * @return Response
     */
    public function show(Program $program):Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'Cette série n\'existe pas.'
            );
        }

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program], ['number' => 'ASC']);

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons
        ]);
    }

    /**
     * Getting a season by seasonId
     *
     * @Route("/{programId}/season/{seasonId}", name="season_show")
     * @ParamConverter ("program", class="App\Entity\Program", options={"mapping": {"programId":"id"}})
     * @ParamConverter ("season", class="App\Entity\Season", options={"mapping": {"seasonId":"id"}})
     * @return Response
     */
    public function showSeason(Program $program, Season $season): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'Cette série n\'existe pas.'
            );
        }
;
        if (!$season) {
            throw $this->createNotFoundException(
                'Cette saison n\'existe pas.'
            );
        }

        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season' => $season->getId()]);
        
        return $this->render('program/season_show.html.twig', [
                'program' => $program,
                'season' => $season,
                'episodes' => $episodes,
            ]);
    }

    /**
     * Getting an episode by episodeId
     *
     * @Route("/{programId}/season/{seasonId}/episode/{episodeId}", name="episode_show")
     * @ParamConverter ("program", class="App\Entity\Program", options={"mapping": {"programId":"id"}})
     * @ParamConverter ("season", class="App\Entity\Season", options={"mapping": {"seasonId":"id"}})
     * @ParamConverter ("episode", class="App\Entity\Episode", options={"mapping": {"episodeId":"id"}})
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'Cette série n\'existe pas.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'Cette saison n\'existe pas.'
            );
        }

        if (!$episode) {
            throw $this->createNotFoundException(
                'Cet épisode n\'existe pas.'
            );
        }
        
        return $this->render('program/episode_show.html.twig', [
                'program' => $program,
                'season' => $season,
                'episode' => $episode,
            ]);
    }
}
