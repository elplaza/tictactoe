<?php

declare(strict_types=1);

namespace TTT\Infrastructure\Doctrine;

use TTT\Domain\GameSession\IGameSession;
use TTT\Application\Query\GameSession\NullGameSession;
use TTT\Application\Query\GameSession\ISearchGameSessions;
use TTT\Infrastructure\Doctrine\Entity\GameSession;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

class SearchGameSessions implements ISearchGameSessions
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function get(UuidInterface $id): IGameSession
    {
        $gameSession = $this->doctrine->getRepository(GameSession::class)->find($id->toString());
        if (empty($gameSession)) {
            return new NullGameSession();
        }

        return $gameSession;
    }

    public function getAll(): array
    {
        return $this->doctrine->getRepository(GameSession::class)->findAll();
    }
}
