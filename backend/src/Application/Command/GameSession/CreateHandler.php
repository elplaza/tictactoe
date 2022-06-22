<?php

declare(strict_types=1);

namespace TTT\Application\Command\GameSession;

use TTT\Application\Command\GameSession\Create as GameSessionCreate;
use TTT\Application\Query\GameSession\IStoreGameSessions;
use TTT\Application\Query\Game\IGame;
use TTT\Application\Query\Game\ISearchGames;
use TTT\Application\Query\Game\NullGame;
use TTT\Application\Query\Player\ISearchPlayers;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateHandler
{
    public function __construct(
        private IStoreGameSessions $storeGameSessions,
        private ISearchGames $searchGames,
        private ISearchPlayers $searchPlayers
    ) {
    }

    public function __invoke(GameSessionCreate $message)
    {
        $gameId = $message->getGameId();
        if (empty($gameId)) {
            $game = $this->searchGames->getDefault();
        } else {
            $game = $this->searchGames->get($gameId);
        }

        if ($game instanceof NullGame) {
            throw new \Exception("Game not found");
        }

        $playersCount = $game->getPlayers();
        if (empty($playersCount)) {
            throw new \Exception("Game has not players");
        }


        $players = $this->searchPlayers->getCountedPlayers($playersCount);
        if (count($players) !== $playersCount) {
            throw new \Exception("Not enought players for this game");
        }

        $board = $this->initBoard($game);

        $this->storeGameSessions->create(
            $message->getId(),
            $game,
            $players,
            $board,
            $message->getCreatedAt(),
            $message->getUpdatedAt(),
            $message->isFinished()
        );
    }

    private function initBoard(IGame $game): array
    {
        $boardWidth  = $game->getBoardWidth();
        $boardHeight = $game->getBoardHeight();

        $board = [];
        for ($h = 0; $h < $boardHeight; $h++) {
            $row = [];
            for ($w = 0; $w < $boardWidth; $w++) {
                $row[] = null;
            }

            $board[] = $row;
        }

        return $board;
    }
}
