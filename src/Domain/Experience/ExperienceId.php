<?php

namespace App\Domain\Experience;
use InvalidArgumentException;

final class ExperienceId
{
    private function __construct(private string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('ExperienceId cannot be empty');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function generate(): self
    {
        return new self(bin2hex(random_bytes(16))); // UUID-like
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
