<?php

declare(strict_types=1);

namespace TTT\Application\Query\Player;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

class NullPlayer implements IPlayer
{
    public function getId(): UuidInterface
    {
        return Uuid::uuid4();
    }

    public function getUsername(): ?string
    {
        return null;
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->getId()->toString(),
            'username' => $this->getUsername()
        ];
    }
}
