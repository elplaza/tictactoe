<?php

declare(strict_types=1);

namespace TTT\Application\Query\Game;

use TTT\Application\Query\Game\IGame;
use Ramsey\Uuid\UuidInterface;

interface ISearchGames
{
    public function get(UuidInterface $id): IGame;
    public function getAll(): array;
    public function getDefault(): IGame;
}
