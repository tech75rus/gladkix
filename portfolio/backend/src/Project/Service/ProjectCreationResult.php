<?php

namespace App\Project\Service;

use App\Project\Entity\Project;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProjectCreationResult
{
    public function __construct(
        private bool $success,
        private ?Project $project = null,
        private ?string $error = null,
        private ?array $errorDetails = null,
        private ?ConstraintViolationListInterface $validationErrors = null
    ) {}

    // Фабричные методы для разных сценариев
    public static function success(Project $project): self
    {
        return new self(true, $project);
    }

    public static function validationFailed(ConstraintViolationListInterface $errors): self
    {
        $message = [];
        foreach ($errors as $error) {
            $message[] = $error->getMessage();
        }
        return new self(false, null, 'Validation failed', ['validation_error' => $message]);
    }

    public static function duplicateName(string $name, Project $existingCategory): self
    {
        return new self(false, null, 'Проект с таким названием уже существует', [
            'field' => 'name',
            'value' => $name,
            'existing_category_id' => $existingCategory->getId()
        ]);
    }

    public static function databaseConstraintViolation(\Exception $e): self
    {
        return new self(false, null, 'Database constraint violation', [
            'message' => $e->getMessage()
        ]);
    }

    public static function missingFields(array $missingFields, array $requiredFields = []): self
    {
        return new self(false, null, 'Missing required fields', [
            'missingFields' => $missingFields,
            'requiredFields' => $requiredFields
        ]);
    }

    public static function invalidArgument(string $message): self
    {
        return new self(false, null, 'Invalid argument', [
            'message' => $message
        ]);
    }

    public static function serverError(string $message): self
    {
        return new self(false, null, 'Server error', [
            'message' => $message
        ]);
    }

    // Геттеры
    public function isSuccess(): bool { return $this->success; }
    public function getProject(): ?Project { return $this->project; }
    public function getError(): ?string { return $this->error; }
    public function getErrorDetails(): ?array { return $this->errorDetails; }
    public function getValidationErrors(): ?ConstraintViolationListInterface { return $this->validationErrors; }
}