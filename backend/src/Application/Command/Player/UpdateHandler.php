<?php

declare(strict_types=1);

namespace TTT\Application\Command\Player;

use TTT\Application\Command\Player\Update as UpdatePlayer;
use TTT\Application\Query\Player\IStorePlayers;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateHandler
{
    public function __construct(
        private IStorePlayers $storePlayers
    ) {
    }

    public function __invoke(UpdatePlayer $message)
    {
        $this->storePlayers->update(
            $message->getId(),
            $message->getUsername()
        );
    }
}
