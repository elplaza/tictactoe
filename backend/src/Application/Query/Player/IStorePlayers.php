<?php

declare(strict_types=1);

namespace TTT\Application\Query\Player;

use Ramsey\Uuid\UuidInterface;

interface IStorePlayers
{
    public function create(string $username): void;
    public function update(UuidInterface $id, string $username): void;
}
