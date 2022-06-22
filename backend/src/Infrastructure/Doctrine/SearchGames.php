<?php

declare(strict_types=1);

namespace TTT\Infrastructure\Doctrine;

use TTT\Application\Query\Game\ISearchGames;
use TTT\Application\Query\Game\IGame;
use TTT\Application\Query\Game\NullGame;
use TTT\Infrastructure\Doctrine\Entity\Game;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

class SearchGames implements ISearchGames
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function get(UuidInterface $id): IGame
    {
        $game = $this->doctrine->getRepository(Game::class)->find($id->toString());
        if (empty($game)) {
            return new NullGame();
        }

        return $game;
    }

    public function getDefault(): IGame
    {
        $game = $this->doctrine->getRepository(Game::class)->findOneBy(
            [
                'players'         => 2,
                'boardWidth'      => 3,
                'boardHeight'     => 3,
                'winningSequence' => 3
            ]
        );

        if (empty($game)) {
            return new NullGame();
        }

        return $game;
    }

    public function getAll(): array
    {
        return $this->doctrine->getRepository(Game::class)->findAll();
    }
}
