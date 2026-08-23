<?php

namespace App\Application\Experience\RegisterExperience;

use App\Domain\Experience\Experience;
use App\Domain\Experience\ExperienceId;
use App\Domain\Experience\ExperienceRepository;

final class RegisterExperienceHandler
{
    public function __construct(
        private ExperienceRepository $repository
    ) {}

    public function __invoke(RegisterExperienceCommand $command): void
    {
        $experience = Experience::create(
            $command->title,
            $command->description,
            $command->providerId
        );

        $this->repository->save($experience);
    }
}
