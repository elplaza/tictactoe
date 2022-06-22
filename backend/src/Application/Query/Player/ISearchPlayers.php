<?php

declare(strict_types=1);

namespace TTT\Application\Query\Player;

use TTT\Application\Query\Player\IPlayer;
use Ramsey\Uuid\UuidInterface;

interface ISearchPlayers
{
    public function get(UuidInterface $id): IPlayer;
    public function getAll(): array;
    public function getCountedPlayers(int $count): array;
}
