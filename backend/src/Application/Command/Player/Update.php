<?php

declare(strict_types=1);

namespace TTT\Application\Command\Player;

use Ramsey\Uuid\UuidInterface;

class Update
{
    private UuidInterface $id;
    private string $username;

    public function __construct(
        UuidInterface $id,
        string $username
    ) {
        $this->id       = $id;
        $this->username = $username;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
