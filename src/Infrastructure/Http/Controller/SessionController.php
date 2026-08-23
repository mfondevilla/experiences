<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\Session\CreateSession\CreateSessionCommand;
use App\Application\Session\CreateSession\CreateSessionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class SessionController
{
    public function __construct(private CreateSessionHandler $handler) {}

    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $command = new CreateSessionCommand(
            $data['experienceId'],
            $data['startAt'],
            $data['capacity'],
            $data['price']
        );

        ($this->handler)($command);

        return new JsonResponse(['status' => 'ok']);
    }
}
