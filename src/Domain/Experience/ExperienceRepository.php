<?php

namespace App\Domain\Experience;

interface ExperienceRepository
{
    public function save(Experience $experience): void;

    public function find(ExperienceId $id): ?Experience;
}
