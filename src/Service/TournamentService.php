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
use DomainException;
use Throwable;

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

    /**
     * @param int $id
     *
     * @return bool
     */
    public function has(int $id): bool
    {
        try {
            $this->get($id);
        } catch (Throwable $e) {
            return false;
        }

        return true;
    }

    /**
     * @param int $id
     *
     * @return Tournament
     */
    public function get(int $id): Tournament
    {
        $tournament = $this->tournamentRepository->find($id);
        if ($tournament === null) {
            throw new DomainException(sprintf('Not found tournament with id %s ', $id));
        }

        return $tournament;
    }
}
