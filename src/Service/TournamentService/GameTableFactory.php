<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service\TournamentService;

use App\Service\TournamentService\GameTableFactory\AvailablePairList;
use App\Service\TournamentService\GameTableFactory\DayGameCounter;
use DomainException;

class GameTableFactory
{
    public function createGameTable(
        array $teamList,
        int $maxTeamGamePerDay = 1,
        int $maxGamePerDay = 4
    ): array {
        if (count($teamList) < 2) {
            throw new DomainException("Can't create table for less than two teams");
        }

        $currentDay = 1;
        $dayGameCounter = new DayGameCounter($maxTeamGamePerDay);

        $teamList = array_values($teamList);
        $teamKeyList =  array_keys($teamList);

        $availablePairList = new AvailablePairList($teamKeyList);

        $gameTable = [];
        while ($availablePairList->hasAny()) {
            foreach ($availablePairList->asArray() as $pairKey => $pair) {
                [$teamKeyOne, $teamKeyTwo] = $pair;

                if (
                    !$dayGameCounter->canHaveGame($currentDay, $teamKeyOne)
                    || !$dayGameCounter->canHaveGame($currentDay, $teamKeyTwo)
                ) {
                    continue;
                }

                $gameTable[$currentDay][] = [$teamList[$teamKeyOne], $teamList[$teamKeyTwo]];
                $availablePairList->remove($pairKey);

                $dayGameCounter->increase($currentDay, $teamKeyOne);
                $dayGameCounter->increase($currentDay, $teamKeyTwo);

                if (count($gameTable[$currentDay]) >= $maxGamePerDay) {
                    $currentDay++;
                    continue 2;
                }
            }
            $currentDay++;
        }

        return $gameTable;
    }
}
