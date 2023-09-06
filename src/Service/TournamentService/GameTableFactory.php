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
     * @param array $gameTableCarry
     *
     * @return array
     */
    private function getOptimalPairTable(
        AvailablePairList $availablePairList,
        DayGameCounter $dayGameCounter,
        int $maxGamePerDay,
        int $currentDay = 1,
        array $gameTableCarry = []
    ): array {
        if (!$availablePairList->hasAny()) {
            return $gameTableCarry;
        }

        $gameTable = null;
        $gameTableDayCount = null;
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

            $gameTableCopy = $gameTableCarry;
            $gameTableCopy[$currentDay][] = $pair;

            $currentDayCopy = $currentDay;
            if (count($gameTableCopy[$currentDayCopy]) >= $maxGamePerDay) {
                $currentDayCopy++;
            }

            $gameTableNew = $this->getOptimalPairTable($clonedAvailablePairList, $clonedDayGameCounter, $maxGamePerDay, $currentDayCopy, $gameTableCopy);
            if ($gameTable === null) {
                $gameTable = $gameTableNew;
                $gameTableDayCount = count($gameTable);
            } else {
                $gameTableNewDayCount = count($gameTableNew);
                if ($gameTableNewDayCount < $gameTableDayCount) {
                    $gameTable = $gameTableNew;
                    $gameTableDayCount = $gameTableNewDayCount;
                }
            }
        }

        if ($gameTable === null) {
            $clonedDayGameCounter = clone $dayGameCounter;

            $clonedAvailablePairList = clone $availablePairList;

            $gameTableCopy = $gameTableCarry;

            $currentDayCopy = $currentDay;
            $currentDayCopy++;

            return $this->getOptimalPairTable($clonedAvailablePairList, $clonedDayGameCounter, $maxGamePerDay, $currentDayCopy, $gameTableCopy);
        }

        return $gameTable;
    }
}
