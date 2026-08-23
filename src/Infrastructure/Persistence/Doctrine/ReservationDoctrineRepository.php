<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Reservation\Reservation;
use App\Domain\Reservation\ReservationId;
use App\Domain\Reservation\ReservationRepository;
use App\Domain\Reservation\UserId;
use App\Domain\Reservation\ReservationStatus;
use Doctrine\ORM\EntityManagerInterface;

final class ReservationDoctrineRepository implements ReservationRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Reservation $reservation): void
    {
        $model = $this->em->find(ReservationModel::class, $reservation->id()->value()) ?? new ReservationModel();

        $model->id = $reservation->id()->value();
        $model->sessionId = $reservation->sessionId();
        $model->userId = $reservation->userId()->value();
        $model->seats = $reservation->seats();
        $model->totalPrice = $reservation->totalPrice();
        $model->status = $reservation->status()->value;
        $model->createdAt = $reservation->createdAt();

        $this->em->persist($model);
        $this->em->flush();
    }

    public function find(ReservationId $id): ?Reservation
    {
        $model = $this->em->find(ReservationModel::class, $id->value());

        if (!$model) {
            return null;
        }

        return new Reservation(
            ReservationId::fromString($model->id),
            $model->sessionId,
            UserId::fromString($model->userId),
            $model->seats,
            $model->totalPrice,
            ReservationStatus::from($model->status),
            $model->createdAt
        );
    }

    public function findByUser(UserId $userId): array
    {
        $models = $this->em->getRepository(ReservationModel::class)
            ->findBy(['userId' => $userId->value()]);

        return array_map(fn($m) =>
            new Reservation(
                ReservationId::fromString($m->id),
                $m->sessionId,
                UserId::fromString($m->userId),
                $m->seats,
                $m->totalPrice,
                ReservationStatus::from($m->status),
                $m->createdAt
            ),
        $models);
    }
}
