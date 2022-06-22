<?php

declare(strict_types=1);

namespace TTT\UI\AdminAPI\Controller;

use TTT\Application\Command\Player\Create as CreatePlayerCommand;
use TTT\Application\Command\Player\Update as UpdatePlayerCommand;
use TTT\UI\AdminAPI\DTO\Player\Create as CreatePlayerDTO;
use TTT\UI\AdminAPI\DTO\Player\Update as UpdatePlayerDTO;
use TTT\Application\Query\Player\ISearchPlayers;
use TTT\Application\Query\Player\NullPlayer;
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

class PlayersController extends AbstractController
{
    #[Route('/players', name: 'ttt_get_players', methods: ["GET"])]
    public function getGames(
        ISearchPlayers $searchPlayers,
        NormalizerInterface $normalizer
        //        SerializerInterface $serializer
    ): JsonResponse {
        try {
            $players = $searchPlayers->getAll();
        } catch (\Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()], 500);
        }

        return new JsonResponse($normalizer->normalize($players));
    }

    #[Route('/players/{id}', name: 'ttt_get_player', methods: ["GET"])]
    public function getGame(
        ISearchPlayers $searchPlayers,
        NormalizerInterface $normalizer,
        //        SerializerInterface $serializer,
        string $id
    ): JsonResponse {
        try {
            $player = $searchPlayers->get(Uuid::fromString($id));

            if ($player instanceof NullPlayer) {
                return new JsonResponse(["message" => "No game found for id $id"], 404);
            }
        } catch (\Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()], 500);
        }

        return new JsonResponse($normalizer->normalize($player));
    }

    #[Route('/players', name: 'ttt_create_player', methods: ["POST"])]
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

            $dto = CreatePlayerDTO::create($body);

            $command = new CreatePlayerCommand(
                $dto->getUsername()
            );

            $commandBus->dispatch($command);
        } catch (\Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()], 500);
        }

        return new JsonResponse();
    }

    #[Route('/players/{id}', name: 'ttt_update_player', methods: ["PUT"])]
    public function updatePlayer(
        Request $request,
        MessageBusInterface $commandBus,
        ManagerRegistry $doctrine,
        SerializerInterface $serializer,
        string $id
    ): Response {
        try {
            $body = json_decode($request->getContent(), true);
            if (empty($body)) {
                return new JsonResponse(["message" => "Bad request"], 400);
            }

            $dtoData = array_merge(['id' => Uuid::fromString($id)], $body);
            $dto     = UpdatePlayerDTO::create($dtoData);

            $command = new UpdatePlayerCommand(
                $dto->getId(),
                $dto->getUsername()
            );

            $commandBus->dispatch($command);
        } catch (\Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()], 500);
        }

        return new JsonResponse();
    }
}
