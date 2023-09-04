<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service\TournamentService;

use App\Service\TournamentService\GameTableFactory\AvailableTeamKeyPairCollection;
use App\Service\TournamentService\GameTableFactory\DayGameCounter;
use DomainException;

class GameTableFactory
{
    public function createGameTable(
        array $teamList,
        bool $daysSeparation = false,
        int $maxTeamGamePerDay = 1,
        int $maxGamePerDay = 4
    ): array {
        if (count($teamList) < 2) {
            throw new DomainException("Can't create table for less than two teams");
        }

        $currentDay = 1;
        $dayGameCounter = new DayGameCounter($daysSeparation, $maxTeamGamePerDay);

        $teamList = array_values($teamList);
        $teamKeyList =  array_keys($teamList);

        $availablePairs = new AvailableTeamKeyPairCollection($teamKeyList);

        $teamKeyOrderList = $teamKeyList;

        $gameTable = [];
        while ($availablePairs->hasAny()) {
            $teamKeySkipList = [];
            $teamKeyOne = array_shift($teamKeyOrderList);
            while (!$dayGameCounter->canHaveGame($currentDay, $teamKeyOne)) {
                $teamKeySkipList[] = $teamKeyOne;
                $teamKeyOne = array_shift($teamKeyOrderList);
                if ($teamKeyOne === null) {
                    $teamKeyOrderList = $teamKeySkipList;
                    $currentDay++;

                    continue 2;
                }
            }

            if (count($teamKeyOrderList) === 0) {
                $teamKeyOrderList = array_merge([$teamKeyOne], $teamKeySkipList);
                $currentDay++;

                continue;
            }

            $teamKeyTwo = array_shift($teamKeyOrderList);
            while (!$availablePairs->hasPair($teamKeyOne, $teamKeyTwo)
                || !$dayGameCounter->canHaveGame($currentDay, $teamKeyTwo)
            ) {
                $teamKeySkipList[] = $teamKeyTwo;
                $teamKeyTwo = array_shift($teamKeyOrderList);
                if ($teamKeyTwo === null) {
                    $teamKeyOrderList = $teamKeySkipList;
                    $currentDay++;

                    continue 2;
                }
            }

            $teamKeyOrderList = array_merge($teamKeySkipList, $teamKeyOrderList);

            $dayGameCounter->increase($currentDay, $teamKeyOne);
            $dayGameCounter->increase($currentDay, $teamKeyTwo);
            if ($daysSeparation) {
                $gameTable[$currentDay][] = [$teamList[$teamKeyOne], $teamList[$teamKeyTwo]];
                if (count($gameTable[$currentDay]) >= $maxGamePerDay) {
                    $currentDay++;
                }
            } else {
                $gameTable[] = [$teamList[$teamKeyOne], $teamList[$teamKeyTwo]];
            }

            $availablePairs->removePair($teamKeyOne, $teamKeyTwo);
            if ($availablePairs->hasPairFor($teamKeyOne)) {
                $teamKeyOrderList[] = $teamKeyOne;
            }
            if ($availablePairs->hasPairFor($teamKeyTwo)) {
                $teamKeyOrderList[] = $teamKeyTwo;
            }
        }

        return $gameTable;
    }
}
