<?php

declare(strict_types=1);

namespace TTT\Application\Command\GameSession;

use Ramsey\Uuid\UuidInterface;

class Update
{
    private UuidInterface $id;
    private UuidInterface $playerId;
    private int $row;
    private int $column;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        UuidInterface $id,
        UuidInterface $playerId,
        int $row,
        int $column
    ) {
        $this->id        = $id;
        $this->playerId  = $playerId;
        $this->row       = $row;
        $this->column    = $column;
        $this->updatedAt = new \DateTimeImmutable();
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

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
