<?php

namespace App\Application\Reservation\CancelReservation;

use App\Domain\Reservation\ReservationRepository;
use App\Domain\Reservation\ReservationId;
use App\Domain\Session\SessionRepository;
use App\Domain\Session\SessionId;

final class CancelReservationHandler
{
    public function __construct(
        private ReservationRepository $reservationRepository,
        private SessionRepository $sessionRepository
    ) {}

    public function __invoke(CancelReservationCommand $command): void
    { 
        // Usar las propiedades del constructor con $this->
        $reservationId = ReservationId::fromString($command->reservationId);

        $reservation = $this->reservationRepository->findDomain($reservationId);

        if (!$reservation) {
            throw new \RuntimeException('Reservation not found');
        }

        $session = $this->sessionRepository->findDomain(
            SessionId::fromString($reservation->sessionId())
        );

        if (!$session) {
            throw new \RuntimeException('Session not found');
        }

        // Regla de negocio: cancelar reserva y devolver plazas
        $reservation->markCancelled();
        $session->releaseSeats($reservation->seats());
        
        // Persistir cambios
        $this->reservationRepository->save($reservation);
        $this->sessionRepository->save($session);
    }
}
