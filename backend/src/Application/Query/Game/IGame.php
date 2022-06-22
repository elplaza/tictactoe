<?php

declare(strict_types=1);

namespace TTT\Application\Query\Game;

use Ramsey\Uuid\UuidInterface;

interface IGame
{
    public function getId(): UuidInterface;

    public function getPlayers(): int;

    public function getBoardWidth(): int;

    public function getBoardHeight(): int;

    public function getWinningSequence(): int;
}
