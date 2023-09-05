<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Controller\TournamentController;

use App\Entity\Tournament;
use App\Entity\TournamentGame;

class TournamentShowView
{
    /**
     * @param Tournament $tournament
     */
    public function __construct(
        readonly private Tournament $tournament
    ) {}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->tournament->getName();
    }

    /**
     * @return array
     */
    public function getDayToGameListMap(): array
    {
        $dayToGameListMap = [];
        /** @var TournamentGame $game */
        foreach ($this->tournament->getTournamentGameList()->toArray() as $game) {
            $dayToGameListMap[$game->getDay()][] = $game;
        }

        // TODO Sort by time when game time will be added
        foreach ($dayToGameListMap as &$gameList) {
            usort($gameList, static fn (TournamentGame $a, TournamentGame $b) => $a->getTeamOne()->getName() <=> $b->getTeamOne()->getName());
        }
        unset($gameList);

        ksort($dayToGameListMap);

        return $dayToGameListMap;
    }
}
