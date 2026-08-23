<?php

namespace App\Application\Reservation\CancelReservation;

final class CancelReservationCommand
{
    public function __construct(
        public readonly string $reservationId
    ) {}
}
