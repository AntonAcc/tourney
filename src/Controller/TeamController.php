<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Service\TeamService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    public function __construct(
        readonly private TeamService $teamService
    ) {}

    #[Route('/teams', name: 'team_list')]
    public function list(): Response
    {
        $teamList = $this->teamService->findAll();

        $team = new Team('');
        $form = $this->createForm(TeamType::class, $team, ['action' => $this->generateUrl('team_add')]);

        return $this->render('team/list.html.twig', [
            'team_list' => $teamList,
            'team_form' => $form,
        ]);
    }

    #[Route('/team/add', name: 'team_add')]
    public function add(Request $request): Response
    {
        $team = new Team('');
        $form = $this->createForm(TeamType::class, $team, ['action' => $this->generateUrl('team_add')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->teamService->save($team);

            return $this->redirectToRoute('team_list');
        }

        return $this->render('team/add.html.twig', [
            'team_form' => $form,
        ]);
    }
}
