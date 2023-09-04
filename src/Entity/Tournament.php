<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: 'tournamentList')]
    private Collection $teamList;

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: TournamentGame::class, orphanRemoval: true)]
    private Collection $tournamentGameList;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->teamList = new ArrayCollection();
        $this->tournamentGameList = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeamList(): Collection
    {
        return $this->teamList;
    }

    /**
     * @param Team $team
     */
    public function addTeam(Team $team): void
    {
        if (!$this->teamList->contains($team)) {
            $this->teamList->add($team);
        }
    }

    /**
     * @param Team $team
     */
    public function removeTeam(Team $team): void
    {
        $this->teamList->removeElement($team);
    }

    /**
     * @return Collection<int, TournamentGame>
     */
    public function getTournamentGameList(): Collection
    {
        return $this->tournamentGameList;
    }

    /**
     * @param TournamentGame $tournamentGame
     */
    public function addTournamentGame(TournamentGame $tournamentGame): void
    {
        if (!$this->tournamentGameList->contains($tournamentGame)) {
            $this->tournamentGameList->add($tournamentGame);
        }
    }
}
