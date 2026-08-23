<?php

namespace App\Application\Reservation\CancelReservation;

use App\Domain\Reservation\ReservationRepository;
use App\Domain\Reservation\ReservationId;
use App\Domain\Session\SessionRepository;

final class CancelReservationHandler
{
    public function __construct(
        private ReservationRepository $reservationRepository,
        private SessionRepository $sessionRepository
    ) {}

    public function __invoke(CancelReservationCommand $command): void
    {
        $reservation = $reservationRepository->find(ReservationId::fromString($command->reservationId));

        $session = $sessionRepository->find(SessionId::fromString($reservation->sessionId()));

        $reservation->cancel($session);

        $reservationRepository->save($reservation);
        $sessionRepository->save($session);
    }
}
