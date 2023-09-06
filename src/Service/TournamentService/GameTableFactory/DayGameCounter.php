<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Service\TournamentService\GameTableFactory;

class DayGameCounter
{
    private array $gameCount = [];

    public function __construct(
        readonly private int $maxTeamGamePerDay
    ) {}

    /**
     * @param int $day
     * @param int $teamKey
     *
     * @return void
     */
    public function increase(int $day, int $teamKey): void
    {
        if (!isset($this->gameCount[$day][$teamKey])) {
            $this->gameCount[$day][$teamKey] = 1;
        } else {
            $this->gameCount[$day][$teamKey]++;
        }
    }

    /**
     * @param int $day
     * @param int $teamKey
     *
     * @return bool
     */
    public function canTeamHaveGame(int $day, int $teamKey): bool
    {
        return !isset($this->gameCount[$day][$teamKey]) || $this->gameCount[$day][$teamKey] < $this->maxTeamGamePerDay;
    }

    /**
     * @param int $day
     * @param array $teamKeyPair
     *
     * @return bool
     */
    public function canPairHaveGame(int $day, array $teamKeyPair): bool
    {
        [$teamKeyOne, $teamKeyTwo] = $teamKeyPair;

        return $this->canTeamHaveGame($day, $teamKeyOne) && $this->canTeamHaveGame($day, $teamKeyTwo);
    }
}
