<?php

namespace App\Article\Service;

use App\Article\Dto\CreateArticleDto;
use App\Article\Dto\UpdateArticleDto;
use App\Article\Entity\Article;
use App\Article\Factory\ArticleFactory;
use App\Article\Repository\ArticleRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleService
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ArticleFactory $articleFactory,
        private ValidatorInterface $validator
    ) {}

    /**
     * Создание статьи с полной валидацией
     */
    public function createArticle(CreateArticleDto $dto): ArticleCreationResult
    {
        // Проверка уникальности имени
        $existingArticle = $this->articleRepository->findOneBy(['title' => $dto->title]);
        if ($existingArticle) {
            return ArticleCreationResult::duplicateName($dto->title, $existingArticle);
        }

        try {
            // Создание и сохранение
            $article = $this->articleFactory->createFromDto($dto);
            $this->articleRepository->save($article, true);

            return ArticleCreationResult::success($article);

        } catch (UniqueConstraintViolationException $e) {
            return ArticleCreationResult::databaseConstraintViolation($e);
        }
    }

    /**
     * Обновление статьи
     */
    public function updateArticle(int $id, UpdateArticleDto $dto): ArticleUpdateResult
    {
        /** @var Article $article */
        $article = $this->articleRepository->find($id);
        if (!$article) {
            return ArticleUpdateResult::notFound();
        }

        // Валидация DTO
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return ArticleUpdateResult::validationFailed($errors);
        }

        // Проверка уникальности заголовка (если меняется)
        if ($dto->title !== null && $dto->title !== $article->getTitle()) {
            $existingArticle = $this->articleRepository->findOneBy(['title' => $dto->title]);
            if ($existingArticle && $existingArticle->getId() !== $article->getId()) {
                return ArticleUpdateResult::duplicateName($dto->title);
            }
        }

        // Обновление и сохранение
        $this->articleFactory->updateFromDto($article, $dto);
        $this->articleRepository->save($article, true);

        return ArticleUpdateResult::success($article);
    }
}