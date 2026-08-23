<?php

namespace App\Application\Experience\RegisterExperience;

final class RegisterExperienceCommand
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $providerId
    ) {}
}
