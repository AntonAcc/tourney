<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TournamentGameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentGameRepository::class)]
class TournamentGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tournamentGameList')]
    #[ORM\JoinColumn(nullable: false)]
    private Tournament $tournament;

    #[ORM\ManyToOne(inversedBy: 'tournamentGameOneList')]
    #[ORM\JoinColumn(nullable: false)]
    private Team $teamOne;

    #[ORM\ManyToOne(inversedBy: 'tournamentGameTwoList')]
    #[ORM\JoinColumn(nullable: false)]
    private Team $teamTwo;

    #[ORM\Column]
    private ?int $day = null;

    /**
     * @param Tournament $tournament
     * @param Team $teamOne
     * @param Team $teamTwo
     * @param int $day
     */
    public function __construct(Tournament $tournament, Team $teamOne, Team $teamTwo, int $day)
    {
        $this->tournament = $tournament;
        $this->teamOne = $teamOne;
        $this->teamTwo = $teamTwo;
        $this->day = $day;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function getTeamOne(): Team
    {
        return $this->teamOne;
    }

    public function getTeamTwo(): Team
    {
        return $this->teamTwo;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }
}
