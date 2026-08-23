<?php

namespace App\Domain\Session;

use DateTimeImmutable;
use InvalidArgumentException;

final class Session
{
    private function __construct(
        private SessionId $id,
        private string $experienceId,
        private DateTimeImmutable $startAt,
        private int $capacity,
        private int $availableSeats,
        private float $price
    ) {}

    public static function create(
        SessionId $id,
        string $experienceId,
        DateTimeImmutable $startAt,
        int $capacity,
        float $price
    ): self {
        if ($capacity <= 0) {
            throw new InvalidArgumentException('Capacity must be greater than zero');
        }

        if ($startAt < new DateTimeImmutable()) {
            throw new InvalidArgumentException('Cannot create a session in the past');
        }

        return new self($id, $experienceId, $startAt, $capacity, $capacity, $price);
    }

    public function reserveSeats(int $seats): void
    {
        if ($this->startAt < new DateTimeImmutable()) {
            throw new InvalidArgumentException('Cannot reserve seats for a session that has already started');
        }

        if ($seats <= 0) {
            throw new InvalidArgumentException('Seats must be greater than zero');
        }

        if ($seats > $this->availableSeats) {
            throw new InvalidArgumentException('Not enough available seats');
        }

        $this->availableSeats -= $seats;
    }

    public function releaseSeats(int $seats): void
    {
        $this->availableSeats += $seats;

        if ($this->availableSeats > $this->capacity) {
            $this->availableSeats = $this->capacity;
        }
    }

    public function id(): SessionId { return $this->id; }
    public function experienceId(): string { return $this->experienceId; }
    public function startAt(): DateTimeImmutable { return $this->startAt; }
    public function capacity(): int { return $this->capacity; }
    public function availableSeats(): int { return $this->availableSeats; }
    public function price(): float { return $this->price; }
}
