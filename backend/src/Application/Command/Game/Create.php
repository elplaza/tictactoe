<?php

declare(strict_types=1);

namespace TTT\Application\Command\Game;

class Create
{
    private int $players;
    private int $boardWidth;
    private int $boardHeight;
    private int $winningSequence;

    public function __construct(
        int $players,
        int $boardWidth,
        int $boardHeight,
        int $winningSequence
    ) {
        $this->players         = $players;
        $this->boardWidth      = $boardWidth;
        $this->boardHeight     = $boardHeight;
        $this->winningSequence = $winningSequence;
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
