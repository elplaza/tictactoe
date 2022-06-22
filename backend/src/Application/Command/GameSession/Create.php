<?php

declare(strict_types=1);

namespace TTT\Application\Command\GameSession;

use Ramsey\Uuid\UuidInterface;

class Create
{
    private UuidInterface $id;
    private ?UuidInterface $gameId;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;
    private bool $finished;

    public function __construct(
        UuidInterface $id,
        ?UuidInterface $gameId
    ) {
        $this->id        = $id;
        $this->gameId    = $gameId;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->finished  = false;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getGameId(): ?UuidInterface
    {
        return $this->gameId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }
}
