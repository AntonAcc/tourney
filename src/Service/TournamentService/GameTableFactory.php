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

        $pairTable = $this->getOptimalPairTable($availablePairList, $dayGameCounter, $maxGamePerDay);

        $gameTable = [];
        foreach ($pairTable as $day => $pairList) {
            foreach ($pairList as $pair) {
                [$teamKeyOne, $teamKeyTwo] = $pair;
                $gameTable[$day][] = [$teamList[$teamKeyOne], $teamList[$teamKeyTwo]];
            }
        }

        return $gameTable;
    }

    /**
     * @param AvailablePairList $availablePairList
     * @param DayGameCounter $dayGameCounter
     * @param int $currentDay
     * @param int $maxGamePerDay
     * @param array $gameTable
     *
     * @return array
     */
    private function getOptimalPairTable(
        AvailablePairList $availablePairList,
        DayGameCounter $dayGameCounter,
        int $maxGamePerDay,
        int $currentDay = 1,
        array $gameTable = []
    ): array {
        if (!$availablePairList->hasAny()) {
            return $gameTable;
        }

        $gameTableList = [];
        foreach ($availablePairList->asArray() as $pairKey => $pair) {
            [$teamKeyOne, $teamKeyTwo] = $pair;
            if (!$dayGameCounter->canPairHaveGame($currentDay, $pair)) {
                continue;
            }

            $clonedDayGameCounter = clone $dayGameCounter;
            $clonedDayGameCounter->increase($currentDay, $teamKeyOne);
            $clonedDayGameCounter->increase($currentDay, $teamKeyTwo);

            $clonedAvailablePairList = clone $availablePairList;
            $clonedAvailablePairList->remove($pairKey);

            $gameTableCopy = $gameTable;
            $gameTableCopy[$currentDay][] = $pair;

            $currentDayCopy = $currentDay;
            if (count($gameTableCopy[$currentDayCopy]) >= $maxGamePerDay) {
                $currentDayCopy++;
            }

            $gameTableList[] = $this->getOptimalPairTable($clonedAvailablePairList, $clonedDayGameCounter, $maxGamePerDay, $currentDayCopy, $gameTableCopy);
        }

        if (count($gameTableList) === 0) {
            $clonedDayGameCounter = clone $dayGameCounter;

            $clonedAvailablePairList = clone $availablePairList;

            $gameTableCopy = $gameTable;

            $currentDayCopy = $currentDay;
            $currentDayCopy++;

            return $this->getOptimalPairTable($clonedAvailablePairList, $clonedDayGameCounter, $maxGamePerDay, $currentDayCopy, $gameTableCopy);
        }

        usort($gameTableList, static fn (array $a, array $b) => count($a) <=> count($b));

        return array_shift($gameTableList);
    }
}
