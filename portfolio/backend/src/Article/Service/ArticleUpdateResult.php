<?php

namespace App\Article\Service;

use App\Article\Entity\Article;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ArticleUpdateResult
{
    public function __construct(
        private bool $success,
        private ?Article $article = null,
        private ?string $error = null,
        private ?array $errorDetails = null
    ) {}

    public static function success(Article $article): self
    {
        return new self(true, $article);
    }

    public static function notFound(): self
    {
        return new self(false, null, 'Article not found');
    }

    public static function validationFailed(ConstraintViolationListInterface $errors): self
    {
        return new self(false, null, 'Validation failed', ['validation_errors' => $errors]);
    }

    public static function duplicateName(string $name): self
    {
        return new self(false, null, 'Статья с таким названием уже существует', [
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
    public function getArticle(): ?Article { return $this->article; }
    public function getError(): ?string { return $this->error; }
    public function getErrorDetails(): ?array { return $this->errorDetails; }
}