<?php

/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Tests\Service\TournamentService\GameTableFactory;

use App\Service\TournamentService\GameTableFactory\AvailablePairList;
use DomainException;
use PHPUnit\Framework\TestCase;

class AvailablePairListTest extends TestCase
{
    public function testOneTeam(): void
    {
        $teamKeyList = [
            0,
        ];

        $this->expectException(DomainException::class);
        new AvailablePairList($teamKeyList);
    }

    public function testHasAny(): void
    {
        $teamKeyList = [
            0,
            1,
        ];

        $pairList = new AvailablePairList($teamKeyList);

        $this->assertTrue($pairList->hasAny());
        $pairList->remove(0);
        $this->assertFalse($pairList->hasAny());
    }

    public function testConstructorTwoTeams(): void
    {
        $teamKeyList = [
            0,
            1,
        ];

        $pairList = new AvailablePairList($teamKeyList);

        $this->assertEquals([
            [0, 1],
        ], $pairList->asArray());
    }

    public function testConstructorThreeTeams(): void
    {
        $teamKeyList = [
            0,
            1,
            2,
        ];

        $pairList = new AvailablePairList($teamKeyList);

        $this->assertEquals([
            [0, 1],
            [2, 0],
            [1, 2],
        ], $pairList->asArray());
    }

    public function testConstructorFourTeams(): void
    {
        $teamKeyList = [
            0,
            1,
            2,
            3,
        ];

        $pairList = new AvailablePairList($teamKeyList);

        $this->assertEquals([
            [0, 1],
            [2, 3],
            [0, 2],
            [1, 3],
            [0, 3],
            [2, 1],
        ], $pairList->asArray());
    }
}

