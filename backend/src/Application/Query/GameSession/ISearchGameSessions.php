<?php

declare(strict_types=1);

namespace TTT\Application\Query\GameSession;

use TTT\Domain\GameSession\IGameSession;
use Ramsey\Uuid\UuidInterface;

interface ISearchGameSessions
{
    public function get(UuidInterface $id): IGameSession;
    public function getAll(): array;
}
