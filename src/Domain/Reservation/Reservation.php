<?php

namespace App\Domain\Reservation;

use App\Domain\Session\Session;
use DateTimeImmutable;
use InvalidArgumentException;

final class Reservation
{
    private function __construct(
        private ReservationId $id,
        private string $sessionId,
        private UserId $userId,
        private int $seats,
        private float $totalPrice,
        private ReservationStatus $status,
        private DateTimeImmutable $createdAt
    ) {}

    public static function create(
        ReservationId $id,
        Session $session,
        UserId $userId,
        int $seats
    ): self {
        if ($seats <= 0) {
            throw new InvalidArgumentException('Seats must be greater than zero');
        }

        $session->reserveSeats($seats);

        $totalPrice = $session->price() * $seats;

        return new self(
            $id,
            $session->id()->value(),
            $userId,
            $seats,
            $totalPrice,
            ReservationStatus::CONFIRMED,
            new DateTimeImmutable()
        );
    }

    public function cancel(Session $session): void
    {
        if ($this->status === ReservationStatus::CANCELLED) {
            throw new InvalidArgumentException('Reservation already cancelled');
        }

        $now = new DateTimeImmutable();
        $sessionStart = $session->startAt();

        $diff = $sessionStart->getTimestamp() - $now->getTimestamp();

        if ($diff < 24 * 3600) {
            throw new InvalidArgumentException('Cannot cancel reservation less than 24 hours before the session');
        }

        $this->status = ReservationStatus::CANCELLED;

        $session->releaseSeats($this->seats);
    }

    public function id(): ReservationId { return $this->id; }
    public function sessionId(): string { return $this->sessionId; }
    public function userId(): UserId { return $this->userId; }
    public function seats(): int { return $this->seats; }
    public function totalPrice(): float { return $this->totalPrice; }
    public function status(): ReservationStatus { return $this->status; }
}
