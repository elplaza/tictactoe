<?php

declare(strict_types=1);

namespace TTT\Application\Query\Player;

use Ramsey\Uuid\UuidInterface;

interface IPlayer
{
    public function getId(): UuidInterface;

    public function getUsername(): ?string;

    public function toArray(): array;
}
