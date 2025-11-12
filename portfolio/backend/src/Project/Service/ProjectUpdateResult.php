<?php

namespace App\Project\Service;

use App\Project\Entity\Project;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProjectUpdateResult
{
    public function __construct(
        private bool $success,
        private ?Project $project = null,
        private ?string $error = null,
        private ?array $errorDetails = null
    ) {}

    public static function success(Project $project): self
    {
        return new self(true, $project);
    }

    public static function notFound(): self
    {
        return new self(false, null, 'Project not found');
    }

    public static function validationFailed(ConstraintViolationListInterface $errors): self
    {
        return new self(false, null, 'Validation failed', ['validation_errors' => $errors]);
    }

    public static function duplicateName(string $name): self
    {
        return new self(false, null, 'Категория с таким названием уже существует', [
            'field' => 'name',
            'value' => $name
        ]);
    }

    /**
     * Отсутствуют обязательные поля
     */
    public static function missingFields(array $missingFields, array $requiredFields = []): self
    {
        return new self(false, null, 'Missing required fields', [
            'missingFields' => $missingFields,
            'requiredFields' => $requiredFields ?: $missingFields
        ]);
    }

    /**
     * Ошибка сервера
     */
    public static function serverError(string $message = 'Internal server error'): self
    {
        return new self(false, null, 'Server error', [
            'message' => $message,
            'code' => 'INTERNAL_SERVER_ERROR'
        ]);
    }

    // Геттеры
    public function isSuccess(): bool { return $this->success; }
    public function getProject(): ?Project { return $this->project; }
    public function getError(): ?string { return $this->error; }
    public function getErrorDetails(): ?array { return $this->errorDetails; }
}