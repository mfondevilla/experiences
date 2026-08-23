<?php

namespace App\Infrastructure\Persistence\Doctrine;
use App\Domain\Reservation\Reservation;
use App\Domain\Reservation\ReservationStatus;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'reservations')]
class ReservationModel
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    public string $id;

    #[ORM\Column(type: 'string')]
    public string $sessionId;

    #[ORM\Column(type: 'string')]
    public string $userId;

    #[ORM\Column(type: 'integer')]
    public int $seats;

    #[ORM\Column(type: 'float')]
    public float $totalPrice;

    #[ORM\Column(type: 'string')]
    public string $status;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $createdAt;

    public static function fromDomain(Reservation $reservation): self
    {
        $model = new self();
        $model->id = $reservation->id()->value();
        $model->sessionId = $reservation->sessionId(); 
        $model->userId = $reservation->userId()->value();
        $model->seats = $reservation->seats();
        $model->totalPrice = $reservation->totalPrice();
        $model->status = $reservation->status()->value;
        $model->createdAt = $reservation->createdAt();

        return $model;
    }


    public function toDomain(): Reservation
    {
        return Reservation::fromPrimitives(
            $this->id,
            $this->sessionId,
            $this->userId,
            $this->seats,  
            $this->totalPrice,
            $this->status,
            $this->createdAt->format('Y-m-d H:i:s')
        );
    }
}