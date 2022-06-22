<?php

declare(strict_types=1);

namespace TTT\UI\AdminAPI\Controller;

use TTT\Application\Command\Game\Create as CreateGameCommand;
use TTT\Application\Command\Game\Update as UpdateGameCommand;
use TTT\UI\AdminAPI\DTO\Game\Create as CreateGameDTO;
use TTT\UI\AdminAPI\DTO\Game\Update as UpdateGameDTO;
use TTT\Application\Query\Game\ISearchGames;
use TTT\Application\Query\Game\NullGame;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

class GamesController extends AbstractController
{
    #[Route('/games', name: 'ttt_get_games', methods: ["GET"])]
    public function getGames(
        ISearchGames $searchGames,
        NormalizerInterface $normalizer
        //        SerializerInterface $serializer
    ): JsonResponse {
        try {
            $games = $searchGames->getAll();
        } catch (\Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()], 500);
        }

        return new JsonResponse($normalizer->normalize($games));
    }

    #[Route('/games/{id}', name: 'ttt_get_game', methods: ["GET"])]
    public function getGame(
        ISearchGames $searchGames,
        NormalizerInterface $normalizer,
        //        SerializerInterface $serializer,
        string $id
    ): JsonResponse {
        try {
            $game = $searchGames->get(Uuid::fromString($id));

            if ($game instanceof NullGame) {
                return new JsonResponse(["message" => "No game found for id $id"], 404);
            }
        } catch (\Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()], 500);
        }

        return new JsonResponse($normalizer->normalize($game));
    }

    #[Route('/games', name: 'ttt_create_game', methods: ["POST"])]
    public function createGame(
        Request $request,
        MessageBusInterface $commandBus,
        ManagerRegistry $doctrine
    ): JsonResponse {
        try {
            $body = json_decode($request->getContent(), true);
            if (empty($body)) {
                return new JsonResponse(["message" => "Bad request"], 400);
            }

            $dto = CreateGameDTO::create($body);

            $command = new CreateGameCommand(
                $dto->getPlayers(),
                $dto->getBoardWidth(),
                $dto->getBoardHeight(),
                $dto->getWinningSequence()
            );

            $commandBus->dispatch($command);
        } catch (\Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()], 500);
        }

        return new JsonResponse();
    }

    #[Route('/games/{id}', name: 'ttt_update_game', methods: ["PUT"])]
    public function updateGame(
        Request $request,
        MessageBusInterface $commandBus,
        ManagerRegistry $doctrine,
        SerializerInterface $serializer,
        string $id
    ): JsonResponse {
        try {
            $body = json_decode($request->getContent(), true);
            if (empty($body)) {
                return new JsonResponse(["message" => "Bad request"], 400);
            }

            $dtoData = array_merge(['id' => Uuid::fromString($id)], $body);
            $dto     = UpdateGameDTO::create($dtoData);

            $command = new UpdateGameCommand(
                $dto->getId(),
                $dto->getPlayers(),
                $dto->getBoardWidth(),
                $dto->getBoardHeight(),
                $dto->getWinningSequence()
            );

            $commandBus->dispatch($command);
        } catch (\Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()], 500);
        }

        return new JsonResponse();
    }
}
