<?php
/**
 * Generate pairs with maximum evenly order between teams
 *
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service\TournamentService\GameTableFactory;

use DomainException;

class AvailablePairList
{
    private array $pairList = [];

    public function __construct(array $teamKeyList)
    {
        if (count($teamKeyList) < 2) {
            throw new DomainException("Can't create available pairs for less than two teams");
        }

        $availablePairMatrix = new AvailablePairMatrix($teamKeyList);

        $teamKeyOrderList = $teamKeyList;

        while ($availablePairMatrix->hasAny()) {
            $teamKeyOne = array_shift($teamKeyOrderList);
            $teamKeyTwo = array_shift($teamKeyOrderList);
            $teamKeySkipList = [];
            while (!$availablePairMatrix->hasPair($teamKeyOne, $teamKeyTwo)) {
                $teamKeySkipList[] = $teamKeyTwo;
                $teamKeyTwo = array_shift($teamKeyOrderList);
            }

            array_push($teamKeySkipList, ...$teamKeyOrderList);
            $teamKeyOrderList = $teamKeySkipList;

            $this->pairList[] = [$teamKeyOne, $teamKeyTwo];

            $availablePairMatrix->removePair($teamKeyOne, $teamKeyTwo);
            if ($availablePairMatrix->hasPairFor($teamKeyOne)) {
                $teamKeyOrderList[] = $teamKeyOne;
            }
            if ($availablePairMatrix->hasPairFor($teamKeyTwo)) {
                $teamKeyOrderList[] = $teamKeyTwo;
            }
        }
    }

    /**
     * @return bool
     */
    public function hasAny(): bool
    {
        return count($this->pairList) > 0;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return $this->pairList;
    }

    /**
     * @param $id
     */
    public function remove($id): void
    {
        unset($this->pairList[$id]);
    }
}
