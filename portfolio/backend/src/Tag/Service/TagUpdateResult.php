<?php

namespace App\Tag\Service;

use App\Tag\Entity\Tag;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class TagUpdateResult
{
    public function __construct(
        private bool $success,
        private ?Tag $tag = null,
        private ?string $error = null,
        private ?array $errorDetails = null
    ) {}

    public static function success(Tag $tag): self
    {
        return new self(true, $tag);
    }

    public static function notFound(): self
    {
        return new self(false, null, 'Tag not found');
    }

    public static function validationFailed(ConstraintViolationListInterface $errors): self
    {
        return new self(false, null, 'Validation failed', ['validation_errors' => $errors]);
    }

    public static function duplicateName(string $name): self
    {
        return new self(false, null, 'Проект с таким названием уже существует', [
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
    public function getTag(): ?Tag { return $this->tag; }
    public function getError(): ?string { return $this->error; }
    public function getErrorDetails(): ?array { return $this->errorDetails; }
}