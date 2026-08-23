<?php

namespace App\Application\Reservation\ReserveSeats;

final class ReserveSeatsCommand
{
    public function __construct(
        public readonly string $sessionId,
        public readonly string $userId,
        public readonly int $seats
    ) {}
}
