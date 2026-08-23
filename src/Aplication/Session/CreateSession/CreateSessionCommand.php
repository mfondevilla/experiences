<?php

namespace App\Application\Session\CreateSession;

final class CreateSessionCommand
{
    public function __construct(
        public readonly string $experienceId,
        public readonly string $startAt,
        public readonly int $capacity,
        public readonly float $price
    ) {}
}
