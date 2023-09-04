<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service;

use App\Entity\Tournament;
use App\Entity\TournamentGame;
use App\Repository\TournamentRepository;
use App\Service\TournamentService\GameTableFactory;
use Doctrine\ORM\EntityManagerInterface;

class TournamentService
{
    /**
     * @param TournamentRepository $tournamentRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        readonly private TournamentRepository $tournamentRepository,
        readonly private EntityManagerInterface $entityManager,
        readonly private GameTableFactory $gameTableFactory
    ) {}

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->tournamentRepository->findAll();
    }

    /**
     * @param Tournament $tournament
     */
    public function save(Tournament $tournament): void
    {
        $tournamentTable = $this->gameTableFactory->createGameTable($tournament->getTeamList()->toArray(), true);
        foreach ($tournamentTable as $day => $dayGameList) {
            foreach ($dayGameList as $dayGame) {
                [$teamOne, $teamTwo] = $dayGame;
                $game = new TournamentGame($tournament, $teamOne, $teamTwo, $day);
                $this->entityManager->persist($game);
            }
        }

        $this->entityManager->persist($tournament);
        $this->entityManager->flush();
    }


}
