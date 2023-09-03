<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service;

use App\Entity\Team;
use App\Repository\TeamRepository;

class TeamService
{
    /**
     * @param TeamRepository $teamRepository
     */
    public function __construct(
        private TeamRepository $teamRepository
    ) {}

    /**
     * @return Team[]
     */
    public function findAll(): array
    {
        return $this->teamRepository->findAll();
    }
}
