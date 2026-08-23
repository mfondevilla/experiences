<?php

namespace App\Domain\Experience;

final class Experience
{
    private function __construct(
        private ExperienceId $id,
        private string $title,
        private string $description,
        private string $providerId
    ) {}

    public static function create(
        ExperienceId $id,
        string $title,
        string $description,
        string $providerId
    ): self {
        if ($title === '') {
            throw new \InvalidArgumentException('Title cannot be empty');
        }

        if ($providerId === '') {
            throw new \InvalidArgumentException('ProviderId cannot be empty');
        }

        return new self($id, $title, $description, $providerId);
    }

    public function id(): ExperienceId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function providerId(): string
    {
        return $this->providerId;
    }
}
