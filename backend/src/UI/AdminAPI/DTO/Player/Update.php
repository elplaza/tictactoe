<?php

declare(strict_types=1);

namespace TTT\UI\AdminAPI\DTO\Player;

use Ramsey\Uuid\UuidInterface;

class Update
{
    private UuidInterface $id;
    private string $username;

    public static function create(array $data): self
    {
        $playerUpdate = new self();

        $playerUpdate->id       = $data['id'];
        $playerUpdate->username = $data['username'] ?? '';

        return $playerUpdate;
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
