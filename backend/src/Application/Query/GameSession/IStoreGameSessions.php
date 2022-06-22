<?php

declare(strict_types=1);

namespace TTT\Application\Query\GameSession;

use TTT\Application\Query\Game\IGame;
use TTT\Application\Query\Player\IPlayer;
use Ramsey\Uuid\UuidInterface;

interface IStoreGameSessions
{
    public function create(
        UuidInterface $id,
        IGame $game,
        array $players,
        array $board,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
        bool $finished
    ): void;

    public function update(
        UuidInterface $id,
        array $board,
        IPlayer $lastPlayer,
        ?IPlayer $winner,
        bool $finished,
        \DateTimeImmutable $updatedAt
    ): void;
}
