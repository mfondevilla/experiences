<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Session\Session;
use App\Domain\Session\SessionId;
use App\Domain\Session\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;

class SessionDoctrineRepository implements SessionRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(Session $session): void
    {
        $model = SessionModel::fromDomain($session);

        $this->em->persist($model);
        $this->em->flush();
    }

    public function find(SessionId $id): ?Session
    {
        $model = $this->em->getRepository(SessionModel::class)
            ->find($id->value());

        if (!$model) {
            return null;
        }

        return $model->toDomain();
    }

    public function findByExperience(string $experienceId): array
    {
        $models = $this->em->getRepository(SessionModel::class)
            ->findBy(['experienceId' => $experienceId]);

        return array_map(fn(SessionModel $m) => $m->toDomain(), $models);
    }
}
