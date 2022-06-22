<?php

namespace TTT\Infrastructure\Doctrine\Entity;

use TTT\Application\Query\Player\IPlayer;
use TTT\Infrastructure\Doctrine\Repository\PlayerRepository;
use TTT\Infrastructure\Doctrine\Entity\GameSession;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\Table(name: "players")]
class Player implements IPlayer
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $username;

    #[ORM\OneToMany(targetEntity: GameSession::class, mappedBy: 'winner')]
    private $winningSessions;

    #[ORM\ManyToMany(targetEntity: GameSession::class, mappedBy: 'players')]
    private $gameSessions;

    public function __construct()
    {
        $this->winningSessions = new ArrayCollection();
        $this->gameSessions = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, GameSession>
     */
    public function getWinningSessions(): Collection
    {
        return $this->winningSessions;
    }

    public function addWinningSession(GameSession $winningSession): self
    {
        if (!$this->winningSessions->contains($winningSession)) {
            $this->winningSessions[] = $winningSession;
            $winningSession->setWinner($this);
        }

        return $this;
    }

    public function removeWinningSession(GameSession $winningSession): self
    {
        if ($this->winningSessions->removeElement($winningSession)) {
            // set the owning side to null (unless already changed)
            if ($winningSession->getWinner() === $this) {
                $winningSession->setWinner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GameSession>
     */
    public function getGameSessions(): Collection
    {
        return $this->gameSessions;
    }

    public function addGameSession(GameSession $gameSession): self
    {
        if (!$this->gameSessions->contains($gameSession)) {
            $this->gameSessions[] = $gameSession;
            $gameSession->addPlayer($this);
        }

        return $this;
    }

    public function removeGameSession(GameSession $gameSession): self
    {
        if ($this->gameSessions->removeElement($gameSession)) {
            $gameSession->removePlayer($this);
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->getId()->toString(),
            'username' => $this->getUsername()
        ];
    }
}
