<?php

namespace App\Application\Reservation;

use App\Domain\Reservation\ReservationRepository;
use App\Infrastructure\Email\FakeEmailSender;

final class CancelReservationService
{
    public function __construct(
        private ReservationRepository $repository,
        private FakeEmailSender $emailSender
    ) {}

    public function __invoke(string $reservationId): void
    {
        $reservation = $this->repository->find($reservationId);

        $reservation->cancel();

        $this->repository->save($reservation);

        $this->emailSender->send(
            $reservation->contactEmail(),
            'Reserva cancelada',
            'Tu reserva ha sido cancelada.'
        );
    }
}
