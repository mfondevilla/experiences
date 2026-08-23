<?php

namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Session\Session;
use App\Domain\Session\SessionId;
use App\Domain\Experience\ExperienceId;

#[ORM\Entity]
#[ORM\Table(name: "sessions")]
class SessionModel
{
    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $id;

    #[ORM\Column(type: "string")]
    private string $experienceId;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $startAt;

    #[ORM\Column(type: "integer")]
    private int $capacity;

    #[ORM\Column(type: "float")]
    private float $price;

    #[ORM\Column(type: "integer")]
    private int $availableSeats;

    public static function fromDomain(Session $session): self
    {
        $model = new self();
        $model->id = $session->id()->value();
        $model->experienceId = $session->experienceId()->value();
        $model->startAt = $session->startAt();
        $model->capacity = $session->capacity();
        $model->price = $session->price();
        $model->availableSeats = $session->availableSeats();

        return $model;
    }

    public function toDomain(): Session
    {
        return new Session(
            SessionId::fromString($this->id),
            ExperienceId::fromString($this->experienceId),
            $this->startAt,
            $this->capacity,
            $this->price,
            $this->availableSeats
        );
    }
}
