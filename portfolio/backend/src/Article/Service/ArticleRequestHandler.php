<?php

namespace App\Article\Service;

use App\Article\Dto\CreateArticleDto;
use App\Article\Dto\UpdateArticleDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Article\Service\ArticleService;

class ArticleRequestHandler
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private ArticleService $articleService
    ) {}

    /**
     * Обработка запроса на создание статьи
     */
    public function handleCreateRequest(Request $request): ArticleCreationResult
    {
        try {
            /** @var CreateArticleDto $createDto */
            $createDto = $this->serializer->deserialize(
                $request->getContent(),
                CreateArticleDto::class,
                'json'
            );

            // Валидация DTO
            $errors = $this->validator->validate($createDto);
            if (count($errors) > 0) {
                return ArticleCreationResult::validationFailed($errors);
            }

            // Делегируем создание в основной сервис
            return $this->articleService->createArticle($createDto);

        } catch (MissingConstructorArgumentsException $e) {
            return ArticleCreationResult::missingFields(
                $e->getMissingConstructorArguments(),
                ['title', 'content'] // обязательные поля
            );
        } catch (\InvalidArgumentException $e) {
            return ArticleCreationResult::invalidArgument($e->getMessage());
        } catch (\Exception $e) {
            return ArticleCreationResult::serverError($e->getMessage());
        }
    }

    /**
     * Обработка запроса на обновление статьи
     */
    public function handleUpdateRequest(int $id, Request $request): ArticleUpdateResult
    {
        try {
            /** @var UpdateArticleDto $updateDto */
            $updateDto = $this->serializer->deserialize(
                $request->getContent(),
                UpdateArticleDto::class,
                'json'
            );
            
            // Валидация DTO
            $errors = $this->validator->validate($updateDto);
            if (count($errors) > 0) {
                return ArticleUpdateResult::validationFailed($errors);
            }
            
            return $this->articleService->updateArticle($id, $updateDto);
            
        } catch (MissingConstructorArgumentsException $e) {
            return ArticleUpdateResult::missingFields($e->getMissingConstructorArguments());
        } catch (\Exception $e) {
            return ArticleUpdateResult::serverError($e->getMessage());
        }
    }
}