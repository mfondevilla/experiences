# Ejecución y pruebas de la API (REST + Docker + PHPUnit)
Este proyecto expone cuatro endpoints principales para gestionar experiencias, sesiones y reservas.
A continuación se presentan las llamadas REST desde terminal y los comandos útiles para probar el sistema.

**1. Registrar una nueva experiencia.**  
Devuelve el id de la experiencia creada necesaria para crear una sesión
curl -X POST http://localhost:8000/experiences \
     -H "Content-Type: application/json" \
     -d '{
           "title":"Experiencia 3",
           "description":"Descripción de la experiencia 3",
           "providerId":"provider-2"
         }'


**2. Crear sesiones para una experiencia.** 
Devuelve el id de la sesión creada necesaria para realizar la reserva de plazas de dicha sesión
curl -X POST http://localhost:8000/sessions \
     -H "Content-Type: application/json" \
     -d '{
           "experienceId":"<IdExperience>",
           "startAt":"2026-09-23 19:00:00",
           "capacity":10,
           "price":35.50
         }'

**3. Reservar plazas para una sesión.** 
Devuelve el id de la reserva
curl -X POST http://localhost:8000/reservations \
     -H "Content-Type: application/json" \
     -d '{
           "sessionId":"<IdSession>",
           "userId":"user-1",
           "seats":2
         }'

**4. Cancelar una reserva.** 
Confirmación de la cancelación y retorno del id de la resrva cancelada
curl -X POST http://localhost:8000/reservations/<IdRserve>/cancel



# Tests con PHPUnit (desde el contenedor Docker)

El test del proyecto se ha ejecutado desde Docker debido a una limitación del entorno local:
- Las versiones php 8.3 no incluían todas las extensiones necearias para instalar y ejecutar PHPUnit
- Las versiones más recientes 8.4 y 8.5 no incluyen las extensiones XML del core (dom, xml, xmlreader, xmlwriter) necesarios para PHPUnit

PHP sí incluye las extensiones necesarias, pero no he conseguido instalar una build funcional de PHP 8.3
Sin embargo un contenedor Docker sí garantiza esta versión y sus extensiones.

Entrar en el contenedor docker 
`docker exec -it NOMBRE_DEL_CONTENEDOR bash`


Comando para realizar test funcionales . Test de creación de una rseerva

`./vendor/bin/phpunit test/Feature/ReservationTest.php`

Ejemplo de reserva usada en los tests:

curl -X POST http://localhost:8000/reservations \
  -H "Content-Type: application/json" \
  -d '{"sessionId":"f05172daae7300d68aa56132b02815e4","userId":"user-1","seats":1}'



