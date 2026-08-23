<?php

namespace App\Domain\Session;

use InvalidArgumentException;

final class SessionId
{
    private function __construct(private string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('SessionId cannot be empty');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function generate(): self
    {
        return new self(bin2hex(random_bytes(16)));
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
