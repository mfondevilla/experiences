<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Reservation\Reservation;
use App\Domain\Reservation\ReservationId;
use App\Domain\Reservation\ReservationRepository;
use App\Domain\Reservation\UserId; 
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationDoctrineRepository extends ServiceEntityRepository implements ReservationRepository
{
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationModel::class);
    }

    public function save(Reservation $reservation): void
    {
        $model = $this->find($reservation->id()->value());

        if ($model === null) {
            $model = ReservationModel::fromDomain($reservation);
            $this->getEntityManager()->persist($model);
        } else {
            $model->sessionId = $reservation->sessionId();
            $model->userId = $reservation->userId()->value();
            $model->seats = $reservation->seats();
            $model->totalPrice = $reservation->totalPrice();
            $model->status = $reservation->status()->value;
            $model->createdAt = $reservation->createdAt();
        }

        $this->getEntityManager()->flush();
    }

    public function findDomain(ReservationId $id): ?Reservation
    {
        $model = $this->em->find(ReservationModel::class, $id->value());
        return $model?->toDomain();
    }
/*
    public function findBySession(string $sessionId): array
    {
        $model = parent::find($id->value());
        return $model?->toDomain();
    }*/
      
    public function findByUser(UserId $userId): array
    {
        $models = $this->createQueryBuilder('r')
            ->where('r.userId = :uid')
            ->setParameter('uid', $userId->value())
            ->getQuery()
            ->getResult();

        return array_map(fn(ReservationModel $m) => $m->toDomain(), $models);
    }
}
