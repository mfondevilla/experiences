<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Reservation\Reservation;
use App\Domain\Reservation\ReservationId;
use App\Domain\Reservation\ReservationRepository;
use App\Domain\Reservation\UserId; 
use Doctrine\ORM\EntityManagerInterface;

class ReservationDoctrineRepository implements ReservationRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(Reservation $reservation): void
    {
         // Buscar si ya existe el modelo en el EntityManager
        $model = $this->em->find(ReservationModel::class, $reservation->id()->value());

        if ($model === null) {
            // Si no existe, creamos uno nuevo desde la entidad de dominio
            $model = ReservationModel::fromDomain($reservation);
            $this->em->persist($model);
        } else {
            // Si ya existe, actualizamos sus campos
            $model->sessionId = $reservation->sessionId();
            $model->userId = $reservation->userId()->value();
            $model->seats = $reservation->seats();
            $model->totalPrice = $reservation->totalPrice();
            $model->status = $reservation->status()->value;
            $model->createdAt = $reservation->createdAt();
        }

        $this->em->flush();
    }

    public function find(ReservationId $id): ?Reservation
    {
        $model = $this->em->getRepository(ReservationModel::class)
            ->find($id->value());

        if (!$model) {
            return null;
        }

        return $model->toDomain();
    }

    public function findBySession(string $sessionId): array
    {
        $models = $this->em->getRepository(ReservationModel::class)
            ->findBy(['sessionId' => $sessionId]);

        return array_map(fn(ReservationModel $m) => $m->toDomain(), $models);
    }

    public function findByUser(UserId $userId): array
    {
        $models = $this->em->getRepository(ReservationModel::class)
            ->findBy(['userId' => $userId->value()]); // 👈 convertir VO a string

        return array_map(fn(ReservationModel $m) => $m->toDomain(), $models);
    }
}
