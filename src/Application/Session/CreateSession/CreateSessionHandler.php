<?php

namespace App\Application\Session\CreateSession;

use App\Domain\Session\Session;
use App\Domain\Session\SessionId;
use App\Domain\Session\SessionRepository;
use DateTimeImmutable;

final class CreateSessionHandler
{
    public function __construct(
        private SessionRepository $repository
    ) {}

    public function __invoke(CreateSessionCommand $command): void
    {
        $session = Session::create(
            $command->experienceId,
            $command->startAt,
            $command->capacity,
            $command->price
        );

        $this->repository->save($session); 
    }
}
