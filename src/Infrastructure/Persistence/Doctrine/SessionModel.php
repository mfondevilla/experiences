<?php

namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'sessions')]
class SessionModel
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    public string $id;

    #[ORM\Column(type: 'string')]
    public string $experienceId;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $startAt;

    #[ORM\Column(type: 'integer')]
    public int $capacity;

    #[ORM\Column(type: 'integer')]
    public int $availableSeats;

    #[ORM\Column(type: 'float')]
    public float $price;
}
