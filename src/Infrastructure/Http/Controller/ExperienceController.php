<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\Experience\RegisterExperience\RegisterExperienceCommand;
use App\Application\Experience\RegisterExperience\RegisterExperienceHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class ExperienceController
{
    public function __construct(private RegisterExperienceHandler $handler) {}

    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $command = new RegisterExperienceCommand(
            $data['title'],
            $data['description'],
            $data['providerId']
        );

        ($this->handler)($command);

        return new JsonResponse(['status' => 'ok']);
    }
}
