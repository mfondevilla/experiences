<?php

namespace App\Tests\Feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Domain\Session\Session;
use App\Domain\Session\SessionId;
use App\Domain\Experience\ExperienceId;
use App\Domain\Session\SessionRepository;

class ReservationTest extends WebTestCase
{
    public function testCreateReservation(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        /** @var SessionRepository $sessionRepository */
        $sessionRepository = $container->get(SessionRepository::class);

        //  Crear una sesión válida para el test
        $session = Session::create(
            experienceId: 'experience-123',
            startAt: '2026-09-25 19:00:00',
            capacity: 10,
            price: 20.0
        );

        $sessionRepository->save($session);

        //  Ejecutar la petición al endpoint
        $client->request(
            'POST',
            '/reservations',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'sessionId' => $session->id()->value(),
                'userId' => 'user-123',
                'seats' => 2,
            ])
        );

        //  Validaciones HTTP
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        // 4Validar contenido JSON
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('reservationId', $data);
        $this->assertSame('ok', $data['status']);
    }
}
