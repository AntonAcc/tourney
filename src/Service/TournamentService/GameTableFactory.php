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
        $teamKeyList =  array_keys($teamList);

        $availablePairList = new AvailablePairList($teamKeyList);

        $pairTable = $this->getOptimalPairTable($availablePairList, $maxGamePerDay);

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
     * @param int $maxGamePerDay
     * @param int $currentDay
     * @param array $scheduledTeamKeyListCarry
     * @param array $gameTableCarry
     *
     * @return array
     */
    private function getOptimalPairTable(
        AvailablePairList $availablePairList,
        int $maxGamePerDay,
        int $currentDay = 1,
        array $scheduledTeamKeyListCarry = [],
        array $gameTableCarry = []
    ): array {
        if (!$availablePairList->hasAny()) {
            return $gameTableCarry;
        }

        $gameTable = null;
        $gameTableDayCount = null;
        foreach ($availablePairList->asArray() as $pairKey => $pair) {
            [$teamKeyOne, $teamKeyTwo] = $pair;
            if (isset($scheduledTeamKeyListCarry[$teamKeyOne]) || isset($scheduledTeamKeyListCarry[$teamKeyTwo])) {
                continue;
            }

            $scheduledTeamKeyListCopy = $scheduledTeamKeyListCarry;
            $scheduledTeamKeyListCopy[$teamKeyOne] = 1;
            $scheduledTeamKeyListCopy[$teamKeyTwo] = 1;

            $clonedAvailablePairList = clone $availablePairList;
            $clonedAvailablePairList->remove($pairKey);

            $gameTableCopy = $gameTableCarry;
            $gameTableCopy[$currentDay][] = $pair;

            $currentDayCopy = $currentDay;
            if (count($gameTableCopy[$currentDayCopy]) >= $maxGamePerDay) {
                $currentDayCopy++;
                $scheduledTeamKeyListCopy = [];
            }

            $gameTableNew = $this->getOptimalPairTable($clonedAvailablePairList, $maxGamePerDay, $currentDayCopy, $scheduledTeamKeyListCopy, $gameTableCopy);
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
            $clonedAvailablePairList = clone $availablePairList;

            $gameTableCopy = $gameTableCarry;

            return $this->getOptimalPairTable($clonedAvailablePairList, $maxGamePerDay, $currentDay + 1, [], $gameTableCopy);
        }

        return $gameTable;
    }
}
