<?php
// src/Category/Service/CategoryCreationResult.php

namespace App\Category\Service;

use App\Category\Entity\Category;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class CategoryCreationResult
{
    public function __construct(
        private bool $success,
        private ?Category $category = null,
        private ?string $error = null,
        private ?array $errorDetails = null,
        private ?ConstraintViolationListInterface $validationErrors = null
    ) {}

    // Фабричные методы для разных сценариев
    public static function success(Category $category): self
    {
        return new self(true, $category);
    }

    public static function validationFailed(ConstraintViolationListInterface $errors): self
    {
        $message = [];
        foreach ($errors as $error) {
            $message[] = $error->getMessage();
        }
        return new self(false, null, 'Validation failed', ['validation_error' => $message]);
    }

    public static function duplicateName(string $name, Category $existingCategory): self
    {
        return new self(false, null, 'Категория с таким названием уже существует', [
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
    public function getCategory(): ?Category { return $this->category; }
    public function getError(): ?string { return $this->error; }
    public function getErrorDetails(): ?array { return $this->errorDetails; }
    public function getValidationErrors(): ?ConstraintViolationListInterface { return $this->validationErrors; }
}