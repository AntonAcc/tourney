<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service;

use App\Entity\Tournament;
use App\Repository\TournamentRepository;
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
        $this->entityManager->persist($tournament);
        $this->entityManager->flush();
    }
}
