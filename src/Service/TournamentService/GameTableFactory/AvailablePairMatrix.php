<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service\TournamentService\GameTableFactory;

use DomainException;

class AvailablePairMatrix
{
    private array $availablePairMatrix;

    /**
     * @param array $teamKeyList
     */
    public function __construct(array $teamKeyList)
    {
        if (count($teamKeyList) < 2) {
            throw new DomainException("Can't create available pairs for less than two teams");
        }

        $this->availablePairMatrix = [];

        foreach ($teamKeyList as $teamKeyOne) {
            foreach ($teamKeyList as $teamKeyTwo) {
                if ($teamKeyOne === $teamKeyTwo) {
                    continue;
                }
                $this->availablePairMatrix[$teamKeyOne][$teamKeyTwo] = true;
                $this->availablePairMatrix[$teamKeyTwo][$teamKeyOne] = true;
            }
        }
    }

    /**
     * @return bool
     */
    public function hasAny(): bool
    {
        return count($this->availablePairMatrix) > 0;
    }

    /**
     * @param int $teamKey
     *
     * @return bool
     */
    public function hasPairFor(int $teamKey): bool
    {
        return isset($this->availablePairMatrix[$teamKey]);
    }

    /**
     * @param int $teamKeyOne
     * @param int $teamKeyTwo
     *
     * @return bool
     */
    public function hasPair(int $teamKeyOne, int $teamKeyTwo): bool
    {
        return isset($this->availablePairMatrix[$teamKeyOne][$teamKeyTwo]);
    }

    /**
     * @param int $teamKeyOne
     * @param int $teamKeyTwo
     *
     * @return void
     */
    public function removePair(int $teamKeyOne, int $teamKeyTwo): void
    {
        unset($this->availablePairMatrix[$teamKeyOne][$teamKeyTwo]);
        if (count($this->availablePairMatrix[$teamKeyOne]) === 0) {
            unset($this->availablePairMatrix[$teamKeyOne]);
        }
        unset($this->availablePairMatrix[$teamKeyTwo][$teamKeyOne]);
        if (count($this->availablePairMatrix[$teamKeyTwo]) === 0) {
            unset($this->availablePairMatrix[$teamKeyTwo]);
        }
    }
}
