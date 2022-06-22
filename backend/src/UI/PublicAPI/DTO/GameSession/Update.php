<?php

declare(strict_types=1);

namespace TTT\UI\PublicAPI\DTO\GameSession;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

class Update
{
    private UuidInterface $id;
    private UuidInterface $playerId;
    private int $row;
    private int $column;

    public static function create(array $data): self
    {
        $gameSessionUpdate = new self();

        $gameSessionUpdate->id       = Uuid::fromString($data['id']);
        $gameSessionUpdate->playerId = Uuid::fromString($data['player_id']);
        $gameSessionUpdate->row      = $data['row'];
        $gameSessionUpdate->column   = $data['column'];

        return $gameSessionUpdate;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPlayerId(): UuidInterface
    {
        return $this->playerId;
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getColumn(): int
    {
        return $this->column;
    }
}
