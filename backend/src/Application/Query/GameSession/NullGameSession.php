<?php

declare(strict_types=1);

namespace TTT\Application\Query\GameSession;

use TTT\Domain\GameSession\IGameSession;
use TTT\Application\Query\Game\IGame;
use TTT\Application\Query\Game\NullGame;
use TTT\Application\Query\Player\IPlayer;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

class NullGameSession implements IGameSession
{
    public function getId(): UuidInterface
    {
        return Uuid::uuid4();
    }

    public function getGame(): IGame
    {
        return new NullGame();
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }

    public function isFinished(): bool
    {
        return false;
    }

    public function getWinner(): ?IPlayer
    {
        return null;
    }

    public function getBoard(): array
    {
        return [];
    }

    public function getLastPlayer(): ?IPlayer
    {
        return null;
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->getId(),
            'board'    => $this->getBoard(),
            'players'  => [],
            'winner'   => null,
            'finished' => $this->isFinished()
        ];
    }
}
