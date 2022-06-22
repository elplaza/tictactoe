<?php

declare(strict_types=1);

namespace TTT\Application\Command\Game;

use TTT\Application\Command\Game\Create as CreateGame;
use TTT\Application\Query\Game\IStoreGames;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateHandler
{
    public function __construct(
        private IStoreGames $storeGames
    ) {
    }

    public function __invoke(CreateGame $message)
    {
        $this->storeGames->create(
            $message->getPlayers(),
            $message->getBoardWidth(),
            $message->getBoardHeight(),
            $message->getWinningSequence()
        );
    }
}
