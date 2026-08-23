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
        $existing = $this->repository->findByExperienceAndDate(
            ExperienceId::fromString($command->experienceId),
            new \DateTimeImmutable($command->startAt)
        );

        if ($existing !== null) {
            throw new \DomainException('A session for this experience already exists on this date');
        }
        
        $session = Session::create(
            $command->experienceId,
            $command->startAt,
            $command->capacity,
            $command->price
        );

        $this->repository->save($session); 
    }
}
