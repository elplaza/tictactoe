<?php

declare(strict_types=1);

namespace TTT\Infrastructure\Doctrine;

use TTT\Application\Query\Game\IStoreGames;
use TTT\Application\Query\Game\IGame;
use TTT\Infrastructure\Doctrine\Entity\Game;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

class StoreGames implements IStoreGames
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function create(
        int $players,
        int $boardWidth,
        int $boardHeight,
        int $winningSequence
    ): void {
        $game = new Game();

        $game
            ->setPlayers($players)
            ->setBoardWidth($boardWidth)
            ->setBoardHeight($boardHeight)
            ->setWinningSequence($winningSequence)
        ;

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($game);
        $entityManager->flush();
    }

    public function update(
        UuidInterface $id,
        int $players,
        int $boardWidth,
        int $boardHeight,
        int $winningSequence
    ): void {
        $game = $this->doctrine->getRepository(Game::class)->find($id->toString());

        if (!empty($game)) {
            $game
                ->setPlayers($players)
                ->setBoardWidth($boardWidth)
                ->setBoardHeight($boardHeight)
                ->setWinningSequence($winningSequence)
            ;

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($game);
            $entityManager->flush();
        }
    }
}
