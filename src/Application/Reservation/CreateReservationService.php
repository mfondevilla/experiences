<?php

namespace App\Application\Reservation;

use App\Domain\Reservation\Reservation;
use App\Domain\Reservation\ReservationRepository;
use App\Infrastructure\Email\FakeEmailSender;

final class CreateReservationService
{
    public function __construct(
        private ReservationRepository $repository,
        private FakeEmailSender $emailSender
    ) {}

    public function __invoke(string $sessionId, string $email, int $seats): Reservation
    {
        $reservation = Reservation::create($sessionId, $email, $seats);

        $this->repository->save($reservation);

        $this->emailSender->send(
            $email,
            'Reserva creada',
            'Tu reserva ha sido creada correctamente.'
        );

        return $reservation;
    }
}
