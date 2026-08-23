<?php

namespace App\Domain\Session;

interface SessionRepository
{
    public function save(Session $session): void;

    public function find(SessionId $id): ?Session;

    /** Opcional si necesitas listar sesiones por experiencia */
    public function findByExperience(string $experienceId): array;
}
