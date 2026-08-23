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

    public static function create(string $title, string $description, string $providerId): self
    {
        return new self(
            ExperienceId::generate(),
            $title,
            $description,
            $providerId
        );
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
