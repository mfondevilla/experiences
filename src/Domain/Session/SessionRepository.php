<?php

namespace App\Domain\Session;
use App\Domain\Experience\ExperienceId; 

interface SessionRepository
{
    public function save(Session $session): void;

    public function find(SessionId $id): ?Session;

    /** Opcional si necesitas listar sesiones por experiencia */
    public function findByExperienceAndDate(ExperienceId $experienceId, \DateTimeImmutable $date): ?Session;

}
