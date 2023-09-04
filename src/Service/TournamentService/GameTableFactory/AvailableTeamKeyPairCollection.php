<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service\TournamentService\GameTableFactory;

use DomainException;

class AvailableTeamKeyPairCollection
{
    private array $teamPairAvailableList;

    /**
     * @param array $teamKeyList
     */
    public function __construct(array $teamKeyList)
    {
        if (count($teamKeyList) < 2) {
            throw new DomainException("Can't create available pairs for less than two teams");
        }

        $this->teamPairAvailableList = [];

        foreach ($teamKeyList as $teamKeyOne) {
            foreach ($teamKeyList as $teamKeyTwo) {
                if ($teamKeyOne === $teamKeyTwo) {
                    continue;
                }
                $this->teamPairAvailableList[$teamKeyOne][$teamKeyTwo] = true;
                $this->teamPairAvailableList[$teamKeyTwo][$teamKeyOne] = true;
            }
        }
    }

    /**
     * @return bool
     */
    public function hasAny(): bool
    {
        return count($this->teamPairAvailableList) > 0;
    }

    /**
     * @param int $teamKey
     *
     * @return bool
     */
    public function hasPairFor(int $teamKey): bool
    {
        return isset($this->teamPairAvailableList[$teamKey]);
    }

    /**
     * @param int $teamKeyOne
     * @param int $teamKeyTwo
     *
     * @return bool
     */
    public function hasPair(int $teamKeyOne, int $teamKeyTwo): bool
    {
        return isset($this->teamPairAvailableList[$teamKeyOne][$teamKeyTwo]);
    }

    /**
     * @param int $teamKeyOne
     * @param int $teamKeyTwo
     *
     * @return void
     */
    public function removePair(int $teamKeyOne, int $teamKeyTwo): void
    {
        unset($this->teamPairAvailableList[$teamKeyOne][$teamKeyTwo]);
        if (count($this->teamPairAvailableList[$teamKeyOne]) === 0) {
            unset($this->teamPairAvailableList[$teamKeyOne]);
        }
        unset($this->teamPairAvailableList[$teamKeyTwo][$teamKeyOne]);
        if (count($this->teamPairAvailableList[$teamKeyTwo]) === 0) {
            unset($this->teamPairAvailableList[$teamKeyTwo]);
        }
    }
}
