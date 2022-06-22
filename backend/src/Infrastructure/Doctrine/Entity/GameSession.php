<?php

namespace TTT\Infrastructure\Doctrine\Entity;

use TTT\Domain\GameSession\IGameSession;
use TTT\Application\Query\Game\IGame;
use TTT\Application\Query\Player\IPlayer;
use TTT\Infrastructure\Doctrine\Repository\GameSessionRepository;
use TTT\Infrastructure\Doctrine\Entity\Game;
use TTT\Infrastructure\Doctrine\Entity\Player;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: GameSessionRepository::class)]
#[ORM\Table(name: "game_sessions")]
class GameSession implements IGameSession
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private $id;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'gameSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private $game;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    #[ORM\Column(type: 'boolean')]
    private $finished;

    #[ORM\ManyToOne(targetEntity: Player::class, inversedBy: 'winningSessions')]
    private $winner;

    #[ORM\ManyToMany(targetEntity: Player::class, inversedBy: 'gameSessions')]
    private $players;

    #[ORM\Column(type: 'json')]
    private $board = [];

    #[ORM\ManyToOne(targetEntity: Player::class)]
    private $lastPlayer;

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getGame(): IGame
    {
        return $this->game;
    }

    public function setGame(IGame $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): self
    {
        $this->finished = $finished;

        return $this;
    }

    public function getWinner(): ?IPlayer
    {
        return $this->winner;
    }

    public function setWinner(?IPlayer $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        $this->players->removeElement($player);

        return $this;
    }

    public function getBoard(): array
    {
        return $this->board;
    }

    public function setBoard(array $board): self
    {
        $this->board = $board;

        return $this;
    }

    public function getLastPlayer(): ?IPlayer
    {
        return $this->lastPlayer;
    }

    public function setLastPlayer(?IPlayer $lastPlayer): self
    {
        $this->lastPlayer = $lastPlayer;

        return $this;
    }

    public function toArray(): array
    {
        $array = [];

        $array['id']    = $this->getId();
        $array['board'] = $this->getBoard();

        $players = [];
        foreach ($this->getPlayers() as $player) {
            $players[] = $player->toArray();
        }

        $array['players'] = $players;

        $winner = $this->getWinner();
        $array['winner'] = (!empty($winner)) ? $winner->toArray() : null;
        $array['finished'] = $this->isFinished();

        return $array;
    }
}
