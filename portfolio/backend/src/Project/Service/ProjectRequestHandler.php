<?php

namespace App\Project\Service;

use App\Project\Dto\CreateProjectDto;
use App\Project\Dto\UpdateProjectDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Project\Service\ProjectService;

class ProjectRequestHandler
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private ProjectService $projectService
    ) {}

    /**
     * Обработка запроса на создание проекта
     */
    public function handleCreateRequest(Request $request): ProjectCreationResult
    {
        try {
            /** @var CreateProjectDto $createDto */
            $createDto = $this->serializer->deserialize(
                $request->getContent(),
                CreateProjectDto::class,
                'json'
            );

            // Валидация DTO
            $errors = $this->validator->validate($createDto);
            if (count($errors) > 0) {
                return ProjectCreationResult::validationFailed($errors);
            }

            // Делегируем создание в основной сервис
            return $this->projectService->createProject($createDto);

        } catch (MissingConstructorArgumentsException $e) {
            return ProjectCreationResult::missingFields(
                $e->getMissingConstructorArguments(),
                ['name'] // обязательные поля
            );
        } catch (\InvalidArgumentException $e) {
            return ProjectCreationResult::invalidArgument($e->getMessage());
        } catch (\Exception $e) {
            return ProjectCreationResult::serverError($e->getMessage());
        }
    }

    /**
     * Обработка запроса на обновление проекта
     */
    public function handleUpdateRequest(int $id, Request $request): ProjectUpdateResult
    {
        try {
            /** @var UpdateProjectDto $updateDto */
            $updateDto = $this->serializer->deserialize(
                $request->getContent(),
                UpdateProjectDto::class,
                'json'
            );
            
            // Валидация DTO
            $errors = $this->validator->validate($updateDto);
            if (count($errors) > 0) {
                return ProjectUpdateResult::validationFailed($errors);
            }
            
            return $this->projectService->updateProject($id, $updateDto);
            
        } catch (MissingConstructorArgumentsException $e) {
            return ProjectUpdateResult::missingFields($e->getMissingConstructorArguments());
        } catch (\Exception $e) {
            return ProjectUpdateResult::serverError($e->getMessage());
        }
    }
}