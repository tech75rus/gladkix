<?php

namespace App\Project\Service;

use App\Project\Dto\CreateProjectDto;
use App\Project\Dto\UpdateProjectDto;
use App\Project\Entity\Project;
use App\Project\Factory\ProjectFactory;
use App\Project\Repository\ProjectRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectFactory $projectFactory,
        private ValidatorInterface $validator
    ) {}

    /**
     * Создание проекта с полной валидацией
     */
    public function createProject(CreateProjectDto $dto): ProjectCreationResult
    {
        // Проверка уникальности имени
        $existingProject = $this->projectRepository->findOneBy(['title' => $dto->title]);
        if ($existingProject) {
            return ProjectCreationResult::duplicateName($dto->title, $existingProject);
        }

        try {
            // Создание и сохранение
            $project = $this->projectFactory->createFromDto($dto);
            $this->projectRepository->save($project, true);

            return ProjectCreationResult::success($project);

        } catch (UniqueConstraintViolationException $e) {
            return ProjectCreationResult::databaseConstraintViolation($e);
        }
    }

    /**
     * Обновление проекта
     */
    public function updateProject(int $id, UpdateProjectDto $dto): ProjectUpdateResult
    {
        /** @var Project $project */
        $project = $this->projectRepository->find($id);
        if (!$project) {
            return ProjectUpdateResult::notFound();
        }

        // Валидация DTO
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return ProjectUpdateResult::validationFailed($errors);
        }

        // Проверка уникальности заголовка (если меняется)
        if ($dto->title !== null && $dto->title !== $project->getTitle()) {
            $existingProject = $this->projectRepository->findOneBy(['name' => $dto->title]);
            if ($existingProject && $existingProject->getId() !== $project->getId()) {
                return ProjectUpdateResult::duplicateName($dto->title);
            }
        }

        // Обновление и сохранение
        $this->projectFactory->updateFromDto($project, $dto);
        $this->projectRepository->save($project, true);

        return ProjectUpdateResult::success($project);
    }
}