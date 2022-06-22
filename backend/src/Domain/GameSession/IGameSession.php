<?php

declare(strict_types=1);

namespace TTT\Domain\GameSession;

use TTT\Application\Query\Game\IGame;
use TTT\Application\Query\Player\IPlayer;
use Ramsey\Uuid\UuidInterface;

interface IGameSession
{
    public function getId(): UuidInterface;

    public function getGame(): IGame;

    public function getCreatedAt(): \DateTimeImmutable;

    public function getUpdatedAt(): \DateTimeImmutable;

    public function isFinished(): bool;

    public function getWinner(): ?IPlayer;

    public function getBoard(): array;

    public function getLastPlayer(): ?IPlayer;

    public function toArray(): array;
}
