Ejecución  llamdas REST desde terminal

1. Registrar una nueva experiencia.
curl -X POST http://localhost:8000/experiences \
     -H "Content-Type: application/json" \
     -d '{
           "title":"Experiencia 1",
           "description":"Descripción de la experiencia 1",
           "providerId":"provider-1"
         }'


2. Crear sesiones para una experiencia.
curl -X POST http://localhost:8000/sessions \
     -H "Content-Type: application/json" \
     -d '{
           "experienceId":"<IdExperience>",
           "startAt":"2026-08-23 19:00:00",
           "capacity":10,
           "price":35.50
         }'

3. Reservar plazas para una sesión.
curl -X POST http://localhost:8000/reservations \
     -H "Content-Type: application/json" \
     -d '{
           "sessionId":"<IdSession>",
           "userId":"user-1",
           "seats":2
         }'

4. Cancelar una reserva.
curl -X POST http://localhost:8000/reservations/<IdRserve>/cancel
