<?php

namespace App\Application\Reservation\ReserveSeats;

use App\Domain\Session\SessionId;
use App\Domain\Session\SessionRepository;
use App\Domain\Reservation\Reservation;
use App\Domain\Reservation\ReservationId;
use App\Domain\Reservation\UserId;
use App\Domain\Reservation\ReservationRepository;

final class ReserveSeatsHandler
{
    public function __construct(
        private SessionRepository $sessionRepository,
        private ReservationRepository $reservationRepository
    ) {}

    public function __invoke(ReserveSeatsCommand $command): ReservationId
    {
        $sessionId = SessionId::fromString($command->sessionId);
        // 1. Recuperamos la sesión desde el repositorio
        $session = $this->sessionRepository->findDomain($sessionId);       

        if ($session === null) {
            throw new \RuntimeException('Session not found: ' . $command->sessionId);
        }
        
        $now = new \DateTimeImmutable();

        if ($session->startAt() <= $now) {
            throw new \DomainException('Cannot reserve a session that has already started');
        }

        // 2. Generamos un nuevo ReservationId
        $reservationId = ReservationId::generate();

        // 3. Creamos la reserva con objetos de dominio
        $reservation = Reservation::create(
            $reservationId,
            $session,
            UserId::fromString($command->userId),
            $command->seats
        );

        // 4. Guardamos la reserva
        $this->reservationRepository->save($reservation);

        // 5. Actualizamos la sesión (ya se reduce seats en Reservation::create())
        $this->sessionRepository->save($session);

        return $reservationId;
    }
}
