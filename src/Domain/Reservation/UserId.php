<?php

namespace App\Domain\Reservation;

use InvalidArgumentException;

final class UserId
{
    private function __construct(private string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('UserId cannot be empty');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
