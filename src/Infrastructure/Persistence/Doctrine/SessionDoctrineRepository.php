<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Experience\ExperienceId; 
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
       // Buscar si ya existe en el EntityManager
        $model = $this->em->find(SessionModel::class, $session->id()->value());

        if ($model === null) {
            // Si no existe, creamos uno nuevo
            $model = SessionModel::fromDomain($session);
            $this->em->persist($model);
        } else {
            // Si existe, actualizamos sus campos
            $model->setExperienceId($session->experienceId()->value());
            $model->setAvailableSeats($session->availableSeats());
            $model->setPrice($session->price());
            $model->setStartAt($session->startAt());
        }

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

    public function findByExperienceAndDate(ExperienceId $experienceId, \DateTimeImmutable $date): ?Session
    {
        return $this->createQueryBuilder('s')
            ->where('s.experienceId = :exp')
            ->andWhere('DATE(s.startAt) = :day')
            ->setParameter('exp', $experienceId->value())
            ->setParameter('day', $date->format('Y-m-d'))
            ->getQuery()
            ->getOneOrNullResult();
    }

}
