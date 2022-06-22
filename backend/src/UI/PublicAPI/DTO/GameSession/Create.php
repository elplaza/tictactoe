<?php

declare(strict_types=1);

namespace TTT\UI\PublicAPI\DTO\GameSession;

use Ramsey\Uuid\UuidInterface;

class Create
{
    private ?UuidInterface $gameId;

    public static function create(?array $data): self
    {
        $gameSessionCreate = new self();

        $gameSessionCreate->gameId = (is_array($data) && isset($data['game_id'])) ? $data['game_id'] : null;

        return $gameSessionCreate;
    }

    public function getGameId(): ?UuidInterface
    {
        return $this->gameId;
    }
}
