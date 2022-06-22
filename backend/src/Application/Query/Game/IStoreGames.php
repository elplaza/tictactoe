<?php

declare(strict_types=1);

namespace TTT\Application\Query\Game;

use Ramsey\Uuid\UuidInterface;

interface IStoreGames
{
    public function create(
        int $players,
        int $boardWidth,
        int $boardHeight,
        int $winningSequence
    ): void;

    public function update(
        UuidInterface $id,
        int $players,
        int $boardWidth,
        int $boardHeight,
        int $winningSequence
    ): void;
}
