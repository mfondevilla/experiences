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
    ) {}

    public static function create(
        string $experienceId,
        string $startAt,
        int $capacity,
        float $price
    ): self {
        return new self(
            SessionId::generate(),
            ExperienceId::fromString($experienceId),
            new \DateTimeImmutable($startAt),
            $capacity,
            $price,
            $capacity
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
