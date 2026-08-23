<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Experience\ExperienceId; 
use App\Domain\Session\Session;
use App\Domain\Session\SessionId;
use App\Domain\Session\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SessionDoctrineRepository extends ServiceEntityRepository implements SessionRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em,
    )
    {
        parent::__construct($registry, SessionModel::class);
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

    public function findDomain(SessionId $id): ?Session
    {
        $model = parent::find($id->value());
        return $model?->toDomain();
    }

    public function findByExperienceAndDate(ExperienceId $experienceId, \DateTimeImmutable $date): ?Session
    {
        $qb = $this->createQueryBuilder('s');

        $qb->where('s.experienceId = :exp')
            ->andWhere('s.startAt >= :start')
            ->andWhere('s.startAt < :end')
            ->setParameter('exp', $experienceId->value())
            ->setParameter('start', $date->setTime(0, 0, 0))
            ->setParameter('end', $date->setTime(23, 59, 59));

        $model = $qb->getQuery()->getOneOrNullResult();

        return $model?->toDomain();
    }

}
