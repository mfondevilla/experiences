<?php

namespace App\Domain\Session;

use App\Domain\Session\SessionId;
use App\Domain\Experience\ExperienceId;

final class Session
{
    private function __construct(
        private SessionId $id,
        private ExperienceId $experienceId,
        private \DateTimeImmutable $startAt,
        private int $capacity,
        private float $price,
        private int $availableSeats
    ) {
        $this->id = $id;
        $this->experienceId = $experienceId;
        $this->startAt = $startAt;   
        $this->capacity = $capacity;
        $this->price = $price;
        $this->availableSeats = $availableSeats;
    }

    public function reserveSeats(int $seats): void
    {
        if ($seats <= 0) {
            throw new \InvalidArgumentException('Seats must be greater than zero');
        }

        if ($seats > $this->availableSeats) {
            throw new \RuntimeException('Not enough seats available');
        }

        $this->availableSeats -= $seats;
    }
    
    public static function create(
        string $experienceId,
        string $startAt,
        int $capacity,
        float $price
    ): self {
        $date = new \DateTimeImmutable($startAt);

        if ($date < new \DateTimeImmutable('today')) {
            throw new \DomainException('Cannot create a session in the past');
        }
        return new self(
            SessionId::generate(),
            ExperienceId::fromString($experienceId),
            new \DateTimeImmutable($startAt),
            $capacity,
            $price,
            $capacity
        );
    }

    // TODO DOCUMENTAR
    public static function fromPrimitives(
        string $id,
        string $experienceId,
        \DateTimeImmutable $startAt,
        int $capacity,
        float $price,
        int $availableSeats
    ): self {
        return new self(
            SessionId::fromString($id),
            ExperienceId::fromString($experienceId),
            $startAt,
            $capacity,
            $price,
            $availableSeats
        );
    }


    public function id(): SessionId
    {
        return $this->id;
    }

    public function experienceId(): ExperienceId
    {
        return $this->experienceId;
    }

    public function startAt(): \DateTimeImmutable
    {
        return $this->startAt;
    }

    public function capacity(): int
    {
        return $this->capacity;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function availableSeats(): int
    {
        return $this->availableSeats;
    }

    public function decreaseSeats(int $seats): void
    {
        $this->availableSeats -= $seats;
    }

    public function increaseSeats(int $seats): void
    {
        $this->availableSeats += $seats;
    }
}
