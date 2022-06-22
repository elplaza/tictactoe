<?php

declare(strict_types=1);

namespace TTT\Application\Query\Game;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

class NullGame implements IGame
{
    public function getId(): UuidInterface
    {
        return Uuid::uuid4();
    }

    public function getPlayers(): int
    {
        return 0;
    }

    public function getBoardWidth(): int
    {
        return 0;
    }

    public function getBoardHeight(): int
    {
        return 0;
    }

    public function getWinningSequence(): int
    {
        return 0;
    }
}
