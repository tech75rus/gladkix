<?php

namespace App\Article\Service;

use App\Article\Entity\Article;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ArticleCreationResult
{
    public function __construct(
        private bool $success,
        private ?Article $article = null,
        private ?string $error = null,
        private ?array $errorDetails = null,
        private ?ConstraintViolationListInterface $validationErrors = null
    ) {}

    // Фабричные методы для разных сценариев
    public static function success(Article $article): self
    {
        return new self(true, $article);
    }

    public static function validationFailed(ConstraintViolationListInterface $errors): self
    {
        $message = [];
        foreach ($errors as $error) {
            $message[] = $error->getMessage();
        }
        return new self(false, null, 'Validation failed', ['validation_error' => $message]);
    }

    public static function duplicateName(string $name, Article $existingCategory): self
    {
        return new self(false, null, 'Статья с таким названием уже существует', [
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
    public function getArticle(): ?Article { return $this->article; }
    public function getError(): ?string { return $this->error; }
    public function getErrorDetails(): ?array { return $this->errorDetails; }
    public function getValidationErrors(): ?ConstraintViolationListInterface { return $this->validationErrors; }
}