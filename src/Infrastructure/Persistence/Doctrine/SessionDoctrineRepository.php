<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Session\Session;
use App\Domain\Session\SessionId;
use App\Domain\Session\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;

final class SessionDoctrineRepository implements SessionRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Session $session): void
    {
        $model = $this->em->find(SessionModel::class, $session->id()->value()) ?? new SessionModel();

        $model->id = $session->id()->value();
        $model->experienceId = $session->experienceId();
        $model->startAt = $session->startAt();
        $model->capacity = $session->capacity();
        $model->availableSeats = $session->availableSeats();
        $model->price = $session->price();

        $this->em->persist($model);
        $this->em->flush();
    }

    public function find(SessionId $id): ?Session
    {
        $model = $this->em->find(SessionModel::class, $id->value());

        if (!$model) {
            return null;
        }

        return Session::create(
            SessionId::fromString($model->id),
            $model->experienceId,
            $model->startAt,
            $model->capacity,
            $model->price
        );
    }

    public function findByExperience(string $experienceId): array
    {
        $models = $this->em->getRepository(SessionModel::class)
            ->findBy(['experienceId' => $experienceId]);

        return array_map(fn($m) =>
            Session::create(
                SessionId::fromString($m->id),
                $m->experienceId,
                $m->startAt,
                $m->capacity,
                $m->price
            ),
        $models);
    }
}
