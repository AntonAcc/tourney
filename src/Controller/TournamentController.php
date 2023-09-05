<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Controller;

use App\Controller\TournamentController\TournamentShowView;
use App\Entity\Tournament;
use App\Form\TournamentType;
use App\Service\TournamentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournamentController extends AbstractController
{
    public function __construct(
        readonly private TournamentService $tournamentService
    ) {}

    #[Route('/', name: 'root')]
    public function listWithLinks(): Response
    {
        $tournamentList = $this->tournamentService->findAll();

        return $this->render('tournament/list_with_links.html.twig', [
            'tournament_list' => $tournamentList,
        ]);
    }

    #[Route('/tournaments', name: 'tournament_list')]
    public function list(): Response
    {
        $tournamentList = $this->tournamentService->findAll();

        $tournament = new Tournament('');
        $form = $this->createForm(TournamentType::class, $tournament, ['action' => $this->generateUrl('tournament_add')]);

        return $this->render('tournament/list.html.twig', [
            'tournament_list' => $tournamentList,
            'tournament_form' => $form,
        ]);
    }

    #[Route('/tournament/add', name: 'tournament_add')]
    public function add(Request $request): Response
    {
        $tournament = new Tournament('');
        $form = $this->createForm(TournamentType::class, $tournament, ['action' => $this->generateUrl('tournament_add')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->tournamentService->save($tournament);

            return $this->redirectToRoute('tournament_list');
        }

        return $this->render('tournament/add.html.twig', [
            'tournament_form' => $form,
        ]);
    }

    #[Route('/tournaments/{id}', name: 'tournament_show')]
    public function show(int $id): Response
    {
        if (!$this->tournamentService->has($id)) {
            return $this->render('tournament/show_error.html.twig', [
                'error' => sprintf('Not found tournament with id %s ', $id),
            ]);
        }

        $tournament = $this->tournamentService->get($id);

        return $this->render('tournament/show.html.twig', [
            'tournament_view' => new TournamentShowView($tournament),
        ]);
    }
}
