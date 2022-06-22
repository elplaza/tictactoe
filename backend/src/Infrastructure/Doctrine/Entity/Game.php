<?php

namespace TTT\Infrastructure\Doctrine\Entity;

use TTT\Application\Query\Game\IGame;
use TTT\Infrastructure\Doctrine\Repository\GameRepository;
use TTT\Infrastructure\Doctrine\Entity\GameSession;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ORM\Table(name: "games")]
class Game implements IGame
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    #[ORM\Column(type: 'smallint')]
    private $players;

    #[ORM\Column(type: 'smallint')]
    private $boardWidth;

    #[ORM\Column(type: 'smallint')]
    private $boardHeight;

    #[ORM\Column(type: 'smallint')]
    private $winningSequence;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: GameSession::class, orphanRemoval: true)]
    private $gameSessions;

    public function __construct()
    {
        $this->gameSessions = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPlayers(): int
    {
        return $this->players;
    }

    public function setPlayers(int $players): self
    {
        $this->players = $players;

        return $this;
    }

    public function getBoardWidth(): int
    {
        return $this->boardWidth;
    }

    public function setBoardWidth(int $boardWidth): self
    {
        $this->boardWidth = $boardWidth;

        return $this;
    }

    public function getBoardHeight(): int
    {
        return $this->boardHeight;
    }

    public function setBoardHeight(int $boardHeight): self
    {
        $this->boardHeight = $boardHeight;

        return $this;
    }

    public function getWinningSequence(): int
    {
        return $this->winningSequence;
    }

    public function setWinningSequence(int $winningSequence): self
    {
        $this->winningSequence = $winningSequence;

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
            $gameSession->setGame($this);
        }

        return $this;
    }
}
