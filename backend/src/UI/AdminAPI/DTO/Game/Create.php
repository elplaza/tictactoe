<?php

declare(strict_types=1);

namespace TTT\UI\AdminAPI\DTO\Game;

class Create
{
    private int $players;
    private int $boardWidth;
    private int $boardHeight;
    private int $winningSequence;

    public static function create(array $data): self
    {
        $gameCreate = new self();

        $gameCreate->players         = $data['players'] ?? 2;
        $gameCreate->boardWidth      = $data['board_width'] ?? 3;
        $gameCreate->boardHeight     = $data['board_height'] ?? 3;
        $gameCreate->winningSequence = $data['winning_sequence'] ?? 3;

        return $gameCreate;
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
