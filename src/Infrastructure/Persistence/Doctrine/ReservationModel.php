<?php

namespace App\Infrastructure\Persistence\Doctrine;

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
}
