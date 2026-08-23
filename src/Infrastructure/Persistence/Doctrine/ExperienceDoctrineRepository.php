<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Experience\Experience;
use App\Domain\Experience\ExperienceId;
use App\Domain\Experience\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ExperienceDoctrineRepository implements ExperienceRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Experience $experience): void
    {
        $model = new ExperienceModel();
        $model->id = $experience->id()->value();
        $model->title = $experience->title();
        $model->description = $experience->description();
        $model->providerId = $experience->providerId();

        $this->em->persist($model);
        $this->em->flush();
    }

    public function find(ExperienceId $id): ?Experience
    {
        $model = $this->em->find(ExperienceModel::class, $id->value());

        if (!$model) {
            return null;
        }

        return Experience::create(
            ExperienceId::fromString($model->id),
            $model->title,
            $model->description,
            $model->providerId
        );
    }
}
