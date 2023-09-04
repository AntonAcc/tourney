<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Tests\Service\TournamentService\GameTableFactory;

use App\Service\TournamentService\GameTableFactory\AvailableTeamKeyPairCollection;
use DomainException;
use PHPUnit\Framework\TestCase;

class AvailableTeamKeyPairCollectionTest extends TestCase
{
    public function testOneTeam(): void
    {
        $teamKeyList = [
            0,
        ];

        $this->expectException(DomainException::class);
        $availableTeamKeyPairCollection = new AvailableTeamKeyPairCollection($teamKeyList);
    }

    public function testTwoTeamsCreating(): void
    {
        $teamKeyList = [
            0,
            1,
        ];

        $availableTeamKeyPairCollection = new AvailableTeamKeyPairCollection($teamKeyList);

        $this->assertTrue($availableTeamKeyPairCollection->hasAny());
        $this->assertTrue($availableTeamKeyPairCollection->hasPairFor(0));
        $this->assertTrue($availableTeamKeyPairCollection->hasPairFor(1));
        $this->assertTrue($availableTeamKeyPairCollection->hasPair(0, 1));
        $this->assertTrue($availableTeamKeyPairCollection->hasPair(1, 0));
    }

    public function testTwoTeamsRemoveOneTwo(): void
    {
        $teamKeyList = [
            0,
            1,
        ];

        $availableTeamKeyPairCollection = new AvailableTeamKeyPairCollection($teamKeyList);
        $availableTeamKeyPairCollection->removePair(0, 1);

        $this->assertFalse($availableTeamKeyPairCollection->hasAny());
        $this->assertFalse($availableTeamKeyPairCollection->hasPairFor(0));
        $this->assertFalse($availableTeamKeyPairCollection->hasPairFor(1));
        $this->assertFalse($availableTeamKeyPairCollection->hasPair(0, 1));
        $this->assertFalse($availableTeamKeyPairCollection->hasPair(1, 0));
    }

    public function testTwoTeamsRemoveTwoOne(): void
    {
        $teamKeyList = [
            0,
            1,
        ];

        $availableTeamKeyPairCollection = new AvailableTeamKeyPairCollection($teamKeyList);
        $availableTeamKeyPairCollection->removePair(1, 0);

        $this->assertFalse($availableTeamKeyPairCollection->hasAny());
        $this->assertFalse($availableTeamKeyPairCollection->hasPairFor(0));
        $this->assertFalse($availableTeamKeyPairCollection->hasPairFor(1));
        $this->assertFalse($availableTeamKeyPairCollection->hasPair(0, 1));
        $this->assertFalse($availableTeamKeyPairCollection->hasPair(1, 0));
    }

    public function testThreeTeamsCreating(): void
    {
        $teamKeyList = [
            0,
            1,
            2,
        ];

        $availableTeamKeyPairCollection = new AvailableTeamKeyPairCollection($teamKeyList);

        $this->assertTrue($availableTeamKeyPairCollection->hasAny());
        $this->assertTrue($availableTeamKeyPairCollection->hasPairFor(0));
        $this->assertTrue($availableTeamKeyPairCollection->hasPairFor(1));
        $this->assertTrue($availableTeamKeyPairCollection->hasPairFor(2));
        $this->assertTrue($availableTeamKeyPairCollection->hasPair(0, 1));
        $this->assertTrue($availableTeamKeyPairCollection->hasPair(0, 2));
        $this->assertTrue($availableTeamKeyPairCollection->hasPair(1, 2));
        $this->assertTrue($availableTeamKeyPairCollection->hasPair(1, 0));
        $this->assertTrue($availableTeamKeyPairCollection->hasPair(2, 0));
        $this->assertTrue($availableTeamKeyPairCollection->hasPair(2, 1));
    }

    public function testThreeTeamsRemove(): void
    {
        $teamKeyList = [
            0,
            1,
            2,
        ];

        $availableTeamKeyPairCollection = new AvailableTeamKeyPairCollection($teamKeyList);
        $availableTeamKeyPairCollection->removePair(0, 1);

        $this->assertTrue($availableTeamKeyPairCollection->hasAny());
        $this->assertTrue($availableTeamKeyPairCollection->hasPairFor(0));
        $this->assertTrue($availableTeamKeyPairCollection->hasPairFor(1));
        $this->assertFalse($availableTeamKeyPairCollection->hasPair(0, 1));
        $this->assertTrue($availableTeamKeyPairCollection->hasPair(0, 2));
        $this->assertTrue($availableTeamKeyPairCollection->hasPair(1, 2));
    }
}

