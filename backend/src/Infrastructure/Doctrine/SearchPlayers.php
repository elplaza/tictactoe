<?php

declare(strict_types=1);

namespace TTT\Infrastructure\Doctrine;

use TTT\Application\Query\Player\ISearchPlayers;
use TTT\Application\Query\Player\IPlayer;
use TTT\Application\Query\Player\NullPlayer;
use TTT\Infrastructure\Doctrine\Entity\Player;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

class SearchPlayers implements ISearchPlayers
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function get(UuidInterface $id): IPlayer
    {
        $player = $this->doctrine->getRepository(Player::class)->find($id->toString());
        if (empty($player)) {
            return new NullPlayer();
        }

        return $player;
    }

    public function getAll(): array
    {
        return $this->doctrine->getRepository(Player::class)->findAll();
    }

    public function getCountedPlayers(int $count): array
    {
        return $this->doctrine->getRepository(Player::class)->findBy([], [], $count);
    }
}
