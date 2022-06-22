<?php

declare(strict_types=1);

namespace TTT\Infrastructure\Doctrine;

use TTT\Application\Query\GameSession\IStoreGameSessions;
use TTT\Application\Query\Game\IGame;
use TTT\Application\Query\Player\IPlayer;
use TTT\Infrastructure\Doctrine\Entity\GameSession;
use TTT\Infrastructure\Doctrine\Entity\Player;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

class StoreGameSessions implements IStoreGameSessions
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function create(
        UuidInterface $id,
        IGame $game,
        array $players,
        array $board,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
        bool $finished
    ): void {
        $gameSession = new GameSession();

        $gameSession
            ->setId($id)
            ->setGame($game)
            ->setBoard($board)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt)
            ->setFinished($finished)
        ;

        foreach ($players as $player) {
            $gameSession->addPlayer($player);
        }

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($gameSession);
        $entityManager->flush();
    }

    public function update(
        UuidInterface $id,
        array $board,
        IPlayer $lastPlayer,
        ?IPlayer $winner,
        bool $finished,
        \DateTimeImmutable $updatedAt
    ): void {
        $gameSession = $this->doctrine->getRepository(GameSession::class)->find($id->toString());

        if (!empty($gameSession)) {
            $gameSession
                ->setBoard($board)
                ->setLastPlayer($lastPlayer)
                ->setWinner($winner)
                ->setFinished($finished)
                ->setUpdatedAt($updatedAt)
            ;

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($gameSession);
            $entityManager->flush();
        }
    }
}
