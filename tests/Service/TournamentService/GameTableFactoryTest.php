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

    public function testTwoTeamsDefault(): void
    {
        $teamList = [
            't1',
            't2',
        ];

        $gameTable = $this->gameTableFactory->createGameTable($teamList);

        $this->assertEquals([
            1 => [
                ['t1', 't2'],
            ]
        ], $gameTable);
    }

    public function testThreeTeamsDefault(): void
    {
        $teamList = [
            't1',
            't2',
            't3',
        ];

        $gameTable = $this->gameTableFactory->createGameTable($teamList);

        $this->assertEquals([
            1 => [
                ['t1', 't2'],
            ],
            2 => [
                ['t3', 't1'],
            ],
            3 => [
                ['t2', 't3'],
            ],
        ], $gameTable);
    }

    public function testFourTeamsDefault(): void
    {
        $teamList = [
            't1',
            't2',
            't3',
            't4',
        ];

        $gameTable = $this->gameTableFactory->createGameTable($teamList);

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

    public function testFiveTeams(): void
    {
        $teamList = [
            't1',
            't2',
            't3',
            't4',
            't5',
        ];

        $gameTable = $this->gameTableFactory->createGameTable($teamList);

        $this->assertEquals([
            1 => [
                ['t1', 't2'],
                ['t3', 't4'],
            ],
            2 => [
                ['t5', 't1'],
                ['t2', 't3'],
            ],
            3 => [
                ['t4', 't5'],
                ['t1', 't3'],
            ],
            4 => [
                ['t2', 't4'],
                ['t5', 't3'],
            ],
            5 => [
                ['t1', 't4'],
                ['t2', 't5'],
            ],
        ], $gameTable);
    }

//    public function testFromSpecification(): void
//    {
//        $teamList = [
//            't1',
//            't2',
//            't3',
//            't4',
//            't5',
//            't6',
//        ];
//
//        $gameTable = $this->gameTableFactory->createGameTable($teamList);
//
//        $this->assertEquals([
//            1 => [
//                ['t1', 't2'],
//                ['t3', 't4'],
//                ['t5', 't6'],
//            ],
//            2 => [
//                ['t1', 't3'],
//                ['t2', 't6'],
//                ['t5', 't4'],
//            ],
//            3 => [
//                ['t1', 't4'],
//                ['t2', 't5'],
//                ['t6', 't3'],
//            ],
//            4 => [
//                ['t1', 't5'],
//                ['t4', 't6'],
//                ['t3', 't2'],
//            ],
//            5 => [
//                ['t1', 't6'],
//                ['t3', 't5'],
//                ['t4', 't2'],
//            ],
//        ], $gameTable);
//    }
}
