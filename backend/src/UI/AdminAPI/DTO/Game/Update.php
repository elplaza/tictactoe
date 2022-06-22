<?php

declare(strict_types=1);

namespace TTT\UI\AdminAPI\DTO\Game;

use Ramsey\Uuid\UuidInterface;

class Update
{
    private UuidInterface $id;
    private int $players;
    private int $boardWidth;
    private int $boardHeight;
    private int $winningSequence;

    public static function create(array $data): self
    {
        $gameUpdate = new self();

        $gameUpdate->id              = $data['id'];
        $gameUpdate->players         = $data['players'] ?? 2;
        $gameUpdate->boardWidth      = $data['board_width'] ?? 3;
        $gameUpdate->boardHeight     = $data['board_height'] ?? 3;
        $gameUpdate->winningSequence = $data['winning_sequence'] ?? 3;

        return $gameUpdate;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPlayers(): int
    {
        return $this->players;
    }

    public function getBoardWidth(): int
    {
        return $this->boardWidth;
    }

    public function getBoardHeight(): int
    {
        return $this->boardHeight;
    }

    public function getWinningSequence(): int
    {
        return $this->winningSequence;
    }
}
