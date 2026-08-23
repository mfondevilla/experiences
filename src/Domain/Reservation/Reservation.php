<?php

namespace App\Domain\Reservation;

use App\Domain\Reservation\ReservationId;
use App\Domain\Reservation\UserId;
use App\Domain\Reservation\ReservationStatus;
use App\Domain\Session\Session;
use DateTimeImmutable;

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

    // Crear nueva reserva con validación y cálculo de precio
    public static function create(
        ReservationId $id,
        Session $session,
        UserId $userId,
        int $seats
    ): self {
        if ($seats <= 0) {
            throw new \InvalidArgumentException('Seats must be greater than zero');
        }

        // Actualiza la sesión (reduce asientos disponibles)
        $session->reserveSeats($seats);

        $totalPrice = $session->price() * $seats;

        return new self(
            $id,
            $session->id()->value(),
            $userId,
            $seats,
            $totalPrice,
            ReservationStatus::CONFIRMED,   // 👈 estado inicial
            new DateTimeImmutable()         // 👈 fecha de creación
        );
    }

    // Reconstruir desde la base de datos
    public static function fromPrimitives(
        string $id,
        string $sessionId,
        string $userId,
        int $seats,
        float $totalPrice,
        string $status,
        string $createdAt
    ): self {
        return new self(
            ReservationId::fromString($id),
            $sessionId,
            UserId::fromString($userId),
            $seats,
            $totalPrice,
            ReservationStatus::from($status),
            new DateTimeImmutable($createdAt)
        );
    }

    public function id(): ReservationId { return $this->id; }
    public function sessionId(): string { return $this->sessionId; }
    public function userId(): UserId { return $this->userId; }
    public function seats(): int { return $this->seats; }
    public function totalPrice(): float { return $this->totalPrice; }
    public function status(): ReservationStatus { return $this->status; }
    public function createdAt(): DateTimeImmutable { return $this->createdAt; }

    public function markCancelled(): void { $this->status = ReservationStatus::CANCELLED; }
    public function markConfirmed(): void { $this->status = ReservationStatus::CONFIRMED; }
}
