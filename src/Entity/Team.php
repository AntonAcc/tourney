<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Tournament::class, mappedBy: 'teamList')]
    private Collection $tournamentList;

    #[ORM\OneToMany(mappedBy: 'teamOne', targetEntity: TournamentGame::class)]
    private Collection $tournamentGameOneList;

    #[ORM\OneToMany(mappedBy: 'teamTwo', targetEntity: TournamentGame::class)]
    private Collection $tournamentGameTwoList;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->tournamentList = new ArrayCollection();
        $this->tournamentGameOneList = new ArrayCollection();
        $this->tournamentGameTwoList = new ArrayCollection();
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Tournament>
     */
    public function getTournamentList(): Collection
    {
        return $this->tournamentList;
    }

    /**
     * @return Collection<int, TournamentGame>
     */
    public function getTournamentGameOneList(): Collection
    {
        return $this->tournamentGameOneList;
    }

    /**
     * @return Collection<int, TournamentGame>
     */
    public function getTournamentGameTwoList(): Collection
    {
        return $this->tournamentGameTwoList;
    }

    /**
     * @return Collection<int, TournamentGame>
     */
    public function getTournamentGameList(): Collection
    {
        return new ArrayCollection(array_merge(
            $this->tournamentGameOneList->toArray(),
            $this->tournamentGameTwoList->toArray()
        ));
    }
}
