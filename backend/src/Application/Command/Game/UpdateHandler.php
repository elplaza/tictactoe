<?php

declare(strict_types=1);

namespace TTT\Application\Command\Game;

use TTT\Application\Command\Game\Update as UpdateGame;
use TTT\Application\Query\Game\IStoreGames;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateHandler
{
    public function __construct(
        private IStoreGames $storeGames
    ) {
    }

    public function __invoke(UpdateGame $message)
    {
        $this->storeGames->update(
            $message->getId(),
            $message->getPlayers(),
            $message->getBoardWidth(),
            $message->getBoardHeight(),
            $message->getWinningSequence()
        );
    }
}
