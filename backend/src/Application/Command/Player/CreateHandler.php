<?php

declare(strict_types=1);

namespace TTT\Application\Command\Player;

use TTT\Application\Command\Player\Create as CreatePlayer;
use TTT\Application\Query\Player\IStorePlayers;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateHandler
{
    public function __construct(
        private IStorePlayers $storePlayers
    ) {
    }

    public function __invoke(CreatePlayer $message)
    {
        $this->storePlayers->create($message->getUsername());
    }
}
