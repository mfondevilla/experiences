<?php

namespace App\Domain\Reservation;

interface ReservationRepository
{
    public function save(Reservation $reservation): void;

    public function find(ReservationId $id): ?Reservation;

    /** Opcional si quieres listar reservas por usuario */
    public function findByUser(UserId $userId): array;
}
