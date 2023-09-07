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

        $pairTable = $this->getOptimalPairTable($availablePairList->asArray(), $availablePairList->asArray(), $maxGamePerDay);

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
     * @param array $availablePairListDay
     * @param array $availablePairListStage
     * @param int $maxGamePerDay
     * @param int $currentDay
     * @param array $scheduledTeamKeyListCarry
     * @param array $gameTableCarry
     *
     * @return array
     */
    private function getOptimalPairTable(
        array $availablePairListDay,
        array $availablePairListStage,
        int   $maxGamePerDay,
        int   $currentDay = 1,
        array $scheduledTeamKeyListCarry = [],
        array $gameTableCarry = []
    ): array
    {
        if (count($availablePairListDay) === 0 && count($availablePairListStage) === 0) {
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
            $gameTableCopy[$currentDay][$pairKey] = $pair;

            if (count($gameTableCopy[$currentDay]) >= $maxGamePerDay) {
                $availablePairListNextDay = array_diff_key($availablePairListDay, $gameTableCopy[$currentDay]);
                $gameTableNew = $this->getOptimalPairTable($availablePairListNextDay, $availablePairListNextDay, $maxGamePerDay, $currentDay + 1, [], $gameTableCopy);
            } else {
                $gameTableNew = $this->getOptimalPairTable($availablePairListDay, $availablePairListStageCut, $maxGamePerDay, $currentDay, $scheduledTeamKeyListCopy, $gameTableCopy);
            }

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
            $availablePairListNextDay = array_diff_key($availablePairListDay, $gameTableCarry[$currentDay]);

            return $this->getOptimalPairTable($availablePairListNextDay, $availablePairListNextDay, $maxGamePerDay, $currentDay + 1, [], $gameTableCarry);
        }

        return $gameTable;
    }
}