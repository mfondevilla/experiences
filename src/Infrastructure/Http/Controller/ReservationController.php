<?php

namespace App\Infrastructure\Http\Controller;

use App\Domain\Reservation\ReservationId;
use App\Application\Reservation\ReserveSeats\ReserveSeatsCommand;
use App\Application\Reservation\ReserveSeats\ReserveSeatsHandler;
use App\Application\Reservation\CancelReservation\CancelReservationCommand;
use App\Application\Reservation\CancelReservation\CancelReservationHandler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class ReservationController
{
    public function __construct(
        private ReserveSeatsHandler $reserveHandler,
        private CancelReservationHandler $cancelHandler
    ) {}

    public function reserve(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $command = new ReserveSeatsCommand(
            $data['sessionId'],
            $data['userId'],
            $data['seats']
        );

        ($this->reserveHandler)($command);

        $reservationId = ($this->reserveHandler)($command);
        return new JsonResponse([
            'reservationId' => $reservationId->value(),
            'status' => 'ok'
        ]);

    }

    public function cancel(string $id): JsonResponse
    {
        $command = new CancelReservationCommand($id);

        ($this->cancelHandler)($command);

        return $this->json([
            'reservationId' => $reservationId->value(),
            'status' => 'ok'
        ]);
    }
}
