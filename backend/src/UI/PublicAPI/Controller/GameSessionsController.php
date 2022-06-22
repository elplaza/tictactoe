<?php

declare(strict_types=1);

namespace TTT\UI\PublicAPI\Controller;

use TTT\Application\Command\GameSession\Create as GameSessionCreateCommand;
use TTT\UI\PublicAPI\DTO\GameSession\Create as GameSessionCreateDTO;
use TTT\Application\Command\GameSession\Update as GameSessionUpdateCommand;
use TTT\UI\PublicAPI\DTO\GameSession\Update as GameSessionUpdateDTO;
use TTT\Application\Query\Game\ISearchGames;
use TTT\Application\Query\GameSession\NullGameSession;
use TTT\Application\Query\Game\NullGame;
use TTT\Application\Query\GameSession\ISearchGameSessions;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

class GameSessionsController extends AbstractController
{
    #[Route('/game-sessions', name: 'ttt_start_new_game', methods: ["POST"])]
    public function createGameSession(
        Request $request,
        MessageBusInterface $commandBus,
        ISearchGameSessions $searchGameSessions
    ): JsonResponse {
        try {
            $body = json_decode($request->getContent(), true);

            $dto = GameSessionCreateDTO::create($body);

            $id = Uuid::uuid4();

            $command = new GameSessionCreateCommand(
                $id,
                $dto->getGameId()
            );

            $commandBus->dispatch($command);

            $gameSession = $searchGameSessions->get($id);
            if ($gameSession instanceof NullGameSession) {
                return new JsonResponse(["message" => "No game session found for id $id"], 500);
            }

            return new JsonResponse($gameSession->toArray());
        } catch (\Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()], 500);
        }
    }

    #[Route('/game-sessions/{id}', name: 'ttt_make_a_move', methods: ["PUT"])]
    public function updateGameSession(
        Request $request,
        MessageBusInterface $commandBus,
        ISearchGameSessions $searchGameSessions,
        string $id
    ): JsonResponse {
        try {
            $body = json_decode($request->getContent(), true);
            if (empty($body)) {
                return new JsonResponse(["message" => "Bad request"], 400);
            }

            $dtoData = array_merge(['id' => $id], $body);
            $dto     = GameSessionUpdateDTO::create($dtoData);

            $command = new GameSessionUpdateCommand(
                $dto->getId(),
                $dto->getPlayerId(),
                $dto->getRow(),
                $dto->getColumn()
            );

            $commandBus->dispatch($command);

            $gameSession = $searchGameSessions->get(Uuid::fromString($id));
            if ($gameSession instanceof NullGameSession) {
                return new JsonResponse(["message" => "No game session found for id $id"], 404);
            }

            return new JsonResponse($gameSession->toArray());
        } catch (\Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()], 500);
        }
    }
}
