<?php

declare(strict_types=1);

namespace TTT\Application\Command\GameSession;

use TTT\Application\Command\GameSession\Update as GameSessionUpdate;
use TTT\Application\Query\GameSession\IStoreGameSessions;
use TTT\Application\Query\GameSession\ISearchGameSessions;
use TTT\Application\Query\Game\IGame;
use TTT\Application\Query\Game\ISearchGames;
use TTT\Application\Query\Game\NullGame;
use TTT\Application\Query\GameSession\NullGameSession;
use TTT\Application\Query\Player\NullPlayer;
use TTT\Application\Query\Player\ISearchPlayers;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateHandler
{
    public function __construct(
        private ISearchGameSessions $searchGameSessions,
        private IStoreGameSessions $storeGameSessions,
        private ISearchGames $searchGames,
        private ISearchPlayers $searchPlayers
    ) {
    }

    public function __invoke(GameSessionUpdate $message)
    {
        $id = $message->getId();

        $gameSession = $this->searchGameSessions->get($id);
        if ($gameSession instanceof NullGameSession) {
            throw new \Exception("GameSession not found");
        }

        if ($gameSession->isFinished()) {
            throw new \Exception("GameSession is finished");
        }

        $playerId = $message->getPlayerId();
        $player = $this->searchPlayers->get($playerId);
        if ($player instanceof NullPlayer) {
            throw new \Exception("Player not found");
        }

        $lastPlayer = $gameSession->getLastPlayer();
        if (!empty($lastPlayer) && $player === $lastPlayer) {
            throw new \Exception("It is not this player's turn");
        }

        $currentBoard = $gameSession->getBoard();
        $row          = $message->getRow();
        $column       = $message->getColumn();
        if (!$this->areCoordinatesValid($row, $column, $currentBoard)) {
            throw new \Exception("Move not valid");
        }

        $playerIdString = $playerId->toString();

        $newBoard = $this->updateBoard($playerIdString, $row, $column, $currentBoard);

        $winningSequence = $gameSession->getGame()->getWinningSequence();
        $won = $this->playerWon($playerIdString, $newBoard, $row, $column, $winningSequence);

        $winner = ($won) ? $player : null;

        $isFinished = $this->isFinished($playerIdString, $newBoard, $row, $column, $winningSequence);

        $this->storeGameSessions->update(
            $id,
            $newBoard,
            $player,
            $winner,
            $isFinished,
            $message->getUpdatedAt()
        );
    }

    private function playerWon(
        string $playerId,
        array $board,
        int $row,
        int $column,
        int $winningSequence
    ): bool {
        return (
            $this->rowWon($playerId, $board, $row, $column, $winningSequence)
            || $this->columnWon($playerId, $board, $row, $column, $winningSequence)
            || $this->slashWon($playerId, $board, $row, $column, $winningSequence)
            || $this->backslashWon($playerId, $board, $row, $column, $winningSequence)
        );
    }

    private function backslashWon(
        string $playerId,
        array $board,
        int $row,
        int $column,
        int $winningSequence
    ): bool {
        // count down right
        $downright = 0;
        $r = $row + 1;
        $d = $column - 1;
        while ($this->isPlayerCell($playerId, $board, $r, $d)) {
            $downright = $downright + 1;
            $d = $d - 1;
            $r = $r + 1;
        }

        // count up left
        $upleft = 0;
        $l = $row - 1;
        $u = $column + 1;
        while ($this->isPlayerCell($playerId, $board, $l, $u)) {
            $upleft = $upleft + 1;
            $u = $u + 1;
            $l = $l - 1;
        }

        $backslashScore = $downright + $upleft + 1;
        return ($backslashScore === $winningSequence);
    }

    private function slashWon(
        string $playerId,
        array $board,
        int $row,
        int $column,
        int $winningSequence
    ): bool {
        // count down left
        $downleft = 0;
        $d = $row - 1;
        $l = $column - 1;
        while ($this->isPlayerCell($playerId, $board, $d, $l)) {
            $downleft = $downleft + 1;
            $d = $d - 1;
            $l = $l - 1;
        }

        // count up right
        $upright = 0;
        $r = $row + 1;
        $u = $column + 1;
        while ($this->isPlayerCell($playerId, $board, $r, $u)) {
            $upright = $upright + 1;
            $u = $u + 1;
            $r = $r + 1;
        }

        $slashScore = $downleft + $upright + 1;
        return ($slashScore === $winningSequence);
    }

    private function columnWon(
        string $playerId,
        array $board,
        int $row,
        int $column,
        int $winningSequence
    ): bool {
        // count down
        $down = 0;
        $d = $row - 1;
        while ($this->isPlayerCell($playerId, $board, $d, $column)) {
            $down = $down + 1;
            $d = $d - 1;
        }

        // count up
        $up = 0;
        $u = $row + 1;
        while ($this->isPlayerCell($playerId, $board, $u, $column)) {
            $up = $up + 1;
            $u = $u + 1;
        }

        $columnScore = $down + $up + 1;
        return ($columnScore === $winningSequence);
    }

    private function rowWon(
        string $playerId,
        array $board,
        int $row,
        int $column,
        int $winningSequence
    ): bool {
        // count left
        $left = 0;
        $l = $column - 1;
        while ($this->isPlayerCell($playerId, $board, $row, $l)) {
            $left = $left + 1;
            $l = $l - 1;
        }

        // count right
        $right = 0;
        $r = $column + 1;
        while ($this->isPlayerCell($playerId, $board, $row, $r)) {
            $right = $right + 1;
            $r = $r + 1;
        }

        $rowScore = $left + $right + 1;
        return ($rowScore === $winningSequence);
    }

    private function isPlayerCell(string $playerId, array $board, int $row, int $column): bool
    {
        return (
            $this->isRowValid($row, $board)
            && $this->isColumnValid($column, $board)
            && $board[$row - 1][$column - 1] === $playerId
        );
    }

    private function isFinished(
        string $playerId,
        array $board,
        int $row,
        int $column,
        int $winningSequence
    ): bool {
        return (
            $this->isBoardFull($board)
            || $this->playerWon($playerId, $board, $row, $column, $winningSequence)
        );
    }

    private function isBoardFull(array $board): bool
    {
        $isFull = true;
        foreach ($board as $row) {
            foreach ($row as $column) {
                if (is_null($column)) {
                    $isFull = false;
                    break;
                }
            }
        }

        return $isFull;
    }

    private function updateBoard(string $playerId, int $row, int $column, array $board): array
    {
        $board[$row - 1][$column - 1] = $playerId;
        return $board;
    }

    private function areCoordinatesValid(int $row, int $column, array $board): bool
    {
        return (
            $this->isRowValid($row, $board)
            && $this->isColumnValid($column, $board)
            && $this->isRowColumnFree($row, $column, $board)
        );
    }

    private function isRowValid(int $row, array $board): bool
    {
        return ($row > 0 && $row <= $this->getRows($board));
    }

    private function isColumnValid(int $column, array $board): bool
    {
        return ($column > 0 && $column <= $this->getColumns($board));
    }

    private function isRowColumnFree(int $row, int $column, array $board): bool
    {
        return is_null($board[$row - 1][$column - 1]);
    }

    private function getRows(array $board): int
    {
        return count($board);
    }

    private function getColumns(array $board): int
    {
        return count($board[0]);
    }
}
