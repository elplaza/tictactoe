<?php

declare(strict_types=1);

namespace TTT\Infrastructure\Doctrine;

use TTT\Application\Query\Player\IStorePlayers;
use TTT\Infrastructure\Doctrine\Entity\Player;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

class StorePlayers implements IStorePlayers
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function create(string $username): void
    {
        $player = new Player();
        $player->setUsername($username);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($player);
        $entityManager->flush();
    }

    public function update(UuidInterface $id, string $username): void
    {
        $player = $this->doctrine->getRepository(Player::class)->find($id->toString());

        if (!empty($player)) {
            $player->setUsername($username);

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($player);
            $entityManager->flush();
        }
    }
}
