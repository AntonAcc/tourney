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
        readonly private bool $daysSeparation,
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
    public function canHaveGame(int $day, int $teamKey): bool
    {
        return !$this->daysSeparation || !isset($this->gameCount[$day][$teamKey]) || $this->gameCount[$day][$teamKey] < $this->maxTeamGamePerDay;
    }
}
