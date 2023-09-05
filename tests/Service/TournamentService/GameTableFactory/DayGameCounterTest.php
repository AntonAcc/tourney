<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Tests\Service\TournamentService\GameTableFactory;

use App\Service\TournamentService\GameTableFactory\DayGameCounter;
use PHPUnit\Framework\TestCase;

class DayGameCounterTest extends TestCase
{
    public function testNoGames(): void
    {
        $counter = new DayGameCounter(1);
        $this->assertTrue($counter->canHaveGame(1, 0));
    }

    public function testOneGame(): void
    {
        $counter = new DayGameCounter(1);
        $counter->increase(1, 0);
        $this->assertFalse($counter->canHaveGame(1, 0));
        $this->assertTrue($counter->canHaveGame(2, 0));
    }
}

