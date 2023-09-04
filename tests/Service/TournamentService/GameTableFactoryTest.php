<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Tests\Service\TournamentService;

use App\Service\TournamentService\GameTableFactory;
use DomainException;
use PHPUnit\Framework\TestCase;

class GameTableFactoryTest extends TestCase
{
    private GameTableFactory $gameTableFactory;

    public function setUp(): void
    {
        $this->gameTableFactory = new GameTableFactory();
    }

    public function testOneTeam(): void
    {
        $teamList = [
            't1',
        ];

        $this->expectException(DomainException::class);
        $this->gameTableFactory->createGameTable($teamList);
    }

    public function testTwoTeams(): void
    {
        $teamList = [
            't1',
            't2',
        ];

        $gameTable = $this->gameTableFactory->createGameTable($teamList);

        $this->assertEquals([
            ['t1', 't2'],
        ], $gameTable);
    }

    public function testThreeTeams(): void
    {
        $teamList = [
            't1',
            't2',
            't3',
        ];

        $gameTable = $this->gameTableFactory->createGameTable($teamList);

        $this->assertEquals([
            ['t1', 't2'],
            ['t3', 't1'],
            ['t2', 't3'],
        ], $gameTable);
    }

    public function testFourTeams(): void
    {
        $teamList = [
            't1',
            't2',
            't3',
            't4',
        ];

        $gameTable = $this->gameTableFactory->createGameTable($teamList);

        $this->assertEquals([
            ['t1', 't2'],
            ['t3', 't4'],
            ['t1', 't3'],
            ['t2', 't4'],
            ['t1', 't4'],
            ['t3', 't2'],
        ], $gameTable);
    }

    public function testFourTeamsDaySeparation(): void
    {
        $teamList = [
            't1',
            't2',
            't3',
            't4',
        ];

        $gameTable = $this->gameTableFactory->createGameTable($teamList, true);

        $this->assertEquals([
            1 => [
                ['t1', 't2'],
                ['t3', 't4'],
            ],
            2 => [
                ['t1', 't3'],
                ['t2', 't4'],
            ],
            3 => [
                ['t1', 't4'],
                ['t3', 't2'],
            ]
        ], $gameTable);
    }

    public function testFourTeamsDaySeparationMaxTeamGameTwo(): void
    {
        $teamList = [
            't1',
            't2',
            't3',
            't4',
        ];

        $gameTable = $this->gameTableFactory->createGameTable($teamList, true, 2, PHP_INT_MAX);

        $this->assertEquals([
            1 => [
                ['t1', 't2'],
                ['t3', 't4'],
                ['t1', 't3'],
                ['t2', 't4'],
            ],
            2 => [
                ['t1', 't4'],
                ['t3', 't2'],
            ]
        ], $gameTable);
    }

    public function testFourTeamsDaySeparationMaxDayGameOne(): void
    {
        $teamList = [
            't1',
            't2',
            't3',
            't4',
        ];

        $gameTable = $this->gameTableFactory->createGameTable($teamList, true, 2, 1);

        $this->assertEquals([
            1 => [
                ['t1', 't2'],
            ],
            2 => [
                ['t3', 't4'],
            ],
            3 => [
                ['t1', 't3'],
            ],
            4 => [
                ['t2', 't4'],
            ],
            5 => [
                ['t1', 't4'],
            ],
            6 => [
                ['t3', 't2'],
            ]
        ], $gameTable);
    }
}
