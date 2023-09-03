<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

class TeamService
{
    /**
     * @param TeamRepository $teamRepository
     */
    public function __construct(
        readonly private TeamRepository $teamRepository,
        readonly private EntityManagerInterface $entityManager,
    ) {}

    /**
     * @return Team[]
     */
    public function findAll(): array
    {
        return $this->teamRepository->findAll();
    }

    /**
     * @param Team $team
     *
     * @return void
     */
    public function save(Team $team): void
    {
        $this->entityManager->persist($team);
        $this->entityManager->flush();
    }
}
