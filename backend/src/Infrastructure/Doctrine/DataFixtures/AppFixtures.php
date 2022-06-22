<?php

namespace TTT\Infrastructure\Doctrine\DataFixtures;

use TTT\Infrastructure\Doctrine\Entity\Game;
use TTT\Infrastructure\Doctrine\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // create 20 players
        for ($i = 0; $i < 20; $i++) {
            $player = new Player();
            $player->setUsername('player '.$i);
            $manager->persist($player);
        }

        // create 3 games
        for ($i = 3; $i < 6; $i++) {
            $game = new Game();
            $game
                ->setPlayers(2)
                ->setBoardWidth($i)
                ->setBoardHeight($i)
                ->setWinningSequence($i)
            ;

            $manager->persist($game);
        }

        $manager->flush();
    }
}
