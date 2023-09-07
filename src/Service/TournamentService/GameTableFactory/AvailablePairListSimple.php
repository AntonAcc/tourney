<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service\TournamentService\GameTableFactory;

use DomainException;

class AvailablePairListSimple
{
    private array $pairList = [];

    public function __construct(array $teamKeyList)
    {
        if (count($teamKeyList) < 2) {
            throw new DomainException("Can't create available pairs for less than two teams");
        }

        foreach ($teamKeyList as $teamKeyKey => $teamKeyOne) {
            $teamKeyListCut = array_slice($teamKeyList, $teamKeyKey + 1);
            foreach ($teamKeyListCut as $teamKeyTwo) {
                $this->pairList[] = [$teamKeyOne, $teamKeyTwo];
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
