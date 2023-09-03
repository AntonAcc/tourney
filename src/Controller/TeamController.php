<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Controller;

use App\Service\TeamService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->render('team/list.html.twig', [
            'team_list' => $teamList,
        ]);
    }
}
