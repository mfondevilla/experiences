<?php

namespace App\Application\Reservation\ReserveSeats;

use App\Domain\Reservation\Reservation;
use App\Domain\Reservation\ReservationId;
use App\Domain\Reservation\UserId;
use App\Domain\Reservation\ReservationRepository;
use App\Domain\Session\SessionRepository;

final class ReserveSeatsHandler
{
    public function __construct(
        private SessionRepository $sessionRepository,
        private ReservationRepository $reservationRepository
    ) {}

    public function __invoke(ReserveSeatsCommand $command): void
    {
        $session = $sessionRepository->find(SessionId::fromString($command->sessionId));

        $reservation = Reservation::create(
            ReservationId::generate(),
            $session,
            UserId::fromString($command->userId),
            $command->seats
        );

        $reservationRepository->save($reservation);
        $sessionRepository->save($session); // seats updated
    }
}
