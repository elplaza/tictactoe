<?php

declare(strict_types=1);

namespace TTT\UI\AdminAPI\DTO\Player;

class Create
{
    private string $username;

    public static function create(array $data): self
    {
        $playerCreate = new self();
        $playerCreate->username = $data['username'] ?? '';

        return $playerCreate;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
