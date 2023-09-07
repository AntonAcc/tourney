<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service\TournamentService;

use App\Service\TournamentService\GameTableFactory\AvailablePairList;
use DomainException;

class GameTableFactory
{
    public function createGameTable(
        array $teamList,
        int $maxGamePerDay = 4
    ): array {
        if (count($teamList) < 2) {
            throw new DomainException("Can't create table for less than two teams");
        }

        $teamList = array_values($teamList);
        $teamKeyList = array_keys($teamList);

        $availablePairList = new AvailablePairList($teamKeyList);

        $pairTable = $this->getOptimalPairTable($availablePairList->asArray(), $maxGamePerDay);

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
     * @param array $availablePairListStage
     * @param int $maxGamePerDay
     *
     * @return array
     */
    private function getOptimalPairTable(
        array $availablePairListStage,
        int   $maxGamePerDay,
    ): array {
        $currentDay = 0;
        $gameTable = [];
        while (count($availablePairListStage) !== 0) {
            $currentDay++;
            $gameTable[$currentDay] = $this->getMaxPairTableForDay($availablePairListStage, $maxGamePerDay);
            $availablePairListStage = array_diff_key($availablePairListStage, $gameTable[$currentDay]);
        }

        return $gameTable;
    }

    /**
     * @param array $availablePairListStage
     * @param int $maxGamePerDay
     * @param array $scheduledTeamKeyListCarry
     * @param array $gameTableCarry
     *
     * @return array
     */
    private function getMaxPairTableForDay(
        array $availablePairListStage,
        int   $maxGamePerDay,
        array $scheduledTeamKeyListCarry = [],
        array $gameTableCarry = []
    ): array {
        if (count($availablePairListStage) === 0) {
            return $gameTableCarry;
        }

        $gameTable = null;
        $gameTableDayCount = null;
        $cutFrom = 0;
        foreach ($availablePairListStage as $pairKey => $pair) {
            $cutFrom++;
            [$teamKeyOne, $teamKeyTwo] = $pair;
            if (isset($scheduledTeamKeyListCarry[$teamKeyOne]) || isset($scheduledTeamKeyListCarry[$teamKeyTwo])) {
                continue;
            }

            $scheduledTeamKeyListCopy = $scheduledTeamKeyListCarry;
            $scheduledTeamKeyListCopy[$teamKeyOne] = 1;
            $scheduledTeamKeyListCopy[$teamKeyTwo] = 1;

            $availablePairListStageCut = array_slice($availablePairListStage, $cutFrom, null, true);

            $gameTableCopy = $gameTableCarry;
            $gameTableCopy[$pairKey] = $pair;

            if (count($gameTableCopy) >= $maxGamePerDay) {
                return $gameTableCopy;
            }

            $gameTableNew = $this->getMaxPairTableForDay($availablePairListStageCut, $maxGamePerDay, $scheduledTeamKeyListCopy, $gameTableCopy);

            if ($gameTable === null) {
                $gameTable = $gameTableNew;
                $gameTableDayCount = count($gameTable);
            } else {
                $gameTableNewDayCount = count($gameTableNew);
                if ($gameTableNewDayCount > $gameTableDayCount) {
                    $gameTable = $gameTableNew;
                    $gameTableDayCount = $gameTableNewDayCount;
                }
            }
        }

        return $gameTable ?? $gameTableCarry;
    }

}
