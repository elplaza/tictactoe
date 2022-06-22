<?php

declare(strict_types=1);

namespace TTT\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameSessionsTest extends WebTestCase
{
    public function testStartGameSession(): void
    {
        $client = self::createClient();

        $client->request(
            'POST',
            "/public/api/game-sessions"
        );

        self::assertResponseIsSuccessful();

        $createdGameSession = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $createdGameSession);
        self::assertArrayHasKey('players', $createdGameSession);

        $players = $createdGameSession['players'];

        self::assertEquals(2, count($players));
        self::assertArrayHasKey('id', $players[0]);
        self::assertArrayHasKey('username', $players[0]);
        self::assertArrayHasKey('id', $players[1]);
        self::assertArrayHasKey('username', $players[1]);

        unset($createdGameSession['id']);
        unset($createdGameSession['players']);

        $expected = $this->getExpectedCreatedGameSession();
        self::assertEquals($expected, $createdGameSession);
    }

    public function testHappyEnding(): void
    {
        $client = self::createClient();

        // create game session
        $client->request(
            'POST',
            "/public/api/game-sessions"
        );

        self::assertResponseIsSuccessful();

        $createdGameSession = json_decode($client->getResponse()->getContent(), true);

        // first move
        $id      = $createdGameSession['id'];
        $players = $createdGameSession['players'];

        $player1 = $players[0];
        $player2 = $players[1];

        $client->request(
            'PUT',
            "/public/api/game-sessions/$id",
            [],
            [],
            [],
            json_encode([
                'id'        => $id,
                'player_id' => $player1['id'],
                'column'    => 1,
                'row'       => 1
            ])
        );

        self::assertResponseIsSuccessful();

        $gameSession1 = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $gameSession1);
        self::assertEquals($id, $gameSession1['id']);
        self::assertArrayHasKey('players', $gameSession1);
        self::assertEquals($players, $gameSession1['players']);

        unset($gameSession1['id']);
        unset($gameSession1['players']);

        $expected = $this->getExpectedGameSession1($player1['id']);
        self::assertEquals($expected, $gameSession1);

        // second move
        $client->request(
            'PUT',
            "/public/api/game-sessions/$id",
            [],
            [],
            [],
            json_encode([
                'id'        => $id,
                'player_id' => $player2['id'],
                'column'    => 1,
                'row'       => 2
            ])
        );

        self::assertResponseIsSuccessful();

        $gameSession2 = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $gameSession2);
        self::assertEquals($id, $gameSession2['id']);
        self::assertArrayHasKey('players', $gameSession2);
        self::assertEquals($players, $gameSession2['players']);

        unset($gameSession2['id']);
        unset($gameSession2['players']);

        $expected = $this->getExpectedGameSession2($player1['id'], $player2['id']);
        self::assertEquals($expected, $gameSession2);

        // third move
        $client->request(
            'PUT',
            "/public/api/game-sessions/$id",
            [],
            [],
            [],
            json_encode([
                'id'        => $id,
                'player_id' => $player1['id'],
                'column'    => 2,
                'row'       => 2
            ])
        );

        self::assertResponseIsSuccessful();

        $gameSession3 = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $gameSession3);
        self::assertEquals($id, $gameSession3['id']);
        self::assertArrayHasKey('players', $gameSession3);
        self::assertEquals($players, $gameSession3['players']);

        unset($gameSession3['id']);
        unset($gameSession3['players']);

        $expected = $this->getExpectedGameSession3($player1['id'], $player2['id']);
        self::assertEquals($expected, $gameSession3);

        // fourth move
        $client->request(
            'PUT',
            "/public/api/game-sessions/$id",
            [],
            [],
            [],
            json_encode([
                'id'        => $id,
                'player_id' => $player2['id'],
                'column'    => 2,
                'row'       => 3
            ])
        );

        self::assertResponseIsSuccessful();

        $gameSession4 = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $gameSession4);
        self::assertEquals($id, $gameSession4['id']);
        self::assertArrayHasKey('players', $gameSession4);
        self::assertEquals($players, $gameSession4['players']);

        unset($gameSession4['id']);
        unset($gameSession4['players']);

        $expected = $this->getExpectedGameSession4($player1['id'], $player2['id']);
        self::assertEquals($expected, $gameSession4);

        // fifth move
        $client->request(
            'PUT',
            "/public/api/game-sessions/$id",
            [],
            [],
            [],
            json_encode([
                'id'        => $id,
                'player_id' => $player1['id'],
                'column'    => 3,
                'row'       => 3
            ])
        );

        self::assertResponseIsSuccessful();

        $gameSession5 = json_decode($client->getResponse()->getContent(), true);


        self::assertArrayHasKey('id', $gameSession5);
        self::assertEquals($id, $gameSession5['id']);
        self::assertArrayHasKey('players', $gameSession5);
        self::assertEquals($players, $gameSession5['players']);

        unset($gameSession5['id']);
        unset($gameSession5['players']);

        $expected = $this->getExpectedGameSession5($player1, $player2['id']);
        self::assertEquals($expected, $gameSession5);
    }

    private function getExpectedCreatedGameSession(): array
    {
        return [
            'winner'   => null,
            'finished' => false,
            'board'    => [
                [null, null, null],
                [null, null, null],
                [null, null, null]
            ]
        ];
    }

    private function getExpectedGameSession1(string $playerId): array
    {
        return [
            'winner'   => null,
            'finished' => false,
            'board'    => [
                [$playerId, null, null],
                [null, null, null],
                [null, null, null]
            ]
        ];
    }

    private function getExpectedGameSession2(string $playerId1, string $playerId2): array
    {
        return [
            'winner'   => null,
            'finished' => false,
            'board'    => [
                [$playerId1, null, null],
                [$playerId2, null, null],
                [null, null, null]
            ]
        ];
    }

    private function getExpectedGameSession3(string $playerId1, string $playerId2): array
    {
        return [
            'winner'   => null,
            'finished' => false,
            'board'    => [
                [$playerId1, null, null],
                [$playerId2, $playerId1, null],
                [null, null, null]
            ]
        ];
    }

    private function getExpectedGameSession4(string $playerId1, string $playerId2): array
    {
        return [
            'winner'   => null,
            'finished' => false,
            'board'    => [
                [$playerId1, null, null],
                [$playerId2, $playerId1, null],
                [null, $playerId2, null]
            ]
        ];
    }

    private function getExpectedGameSession5(array $player1, string $playerId2): array
    {
        $playerId1 = $player1['id'];

        return [
            'winner'   => $player1,
            'finished' => true,
            'board'    => [
                [$playerId1, null, null],
                [$playerId2, $playerId1, null],
                [null, $playerId2, $playerId1]
            ]
        ];
    }
}
