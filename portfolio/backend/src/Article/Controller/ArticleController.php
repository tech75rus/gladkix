<?php

namespace App\Article\Controller;

use App\Article\Entity\Article;
use App\Article\Repository\ArticleRepository;
use App\Article\Service\ArticleRequestHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/articles')]
class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ArticleRequestHandler $articleRequestHandler
    ) {}

    /**
     * Создание статьи
     */
    #[Route('', name: 'article_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $result = $this->articleRequestHandler->handleCreateRequest($request);

        if ($result->isSuccess()) {
            return $this->json([
                'success' => true,
                'message' => 'Статья успешно создана',
                'article' => $this->serializeArticle($result->getArticle())
                ], Response::HTTP_CREATED);
        }

        return $this->handleErrorResult($result);
    }

    /**
     * Получение статьи по ID
     */
    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $article = $this->articleRepository->find($id);
        
        if (!$article) {
            return $this->json(
                ['error' => 'Статья не найдена'], 
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json($this->serializeArticle($article));
    }

    /**
     * Обновление статьи
     */
    #[Route('/{id}', name: 'article_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $result = $this->articleRequestHandler->handleUpdateRequest($id, $request);
        
        if ($result->isSuccess()) {
            return $this->json([
                'success' => true,
                'message' => 'Статья успешно обновлена',
                'article' => $this->serializeArticle($result->getArticle())
            ], Response::HTTP_CREATED);
        }
        
        return $this->handleErrorResult($result);
    }

    /**
     * Удаление статьи
     */
    #[Route('/{id}', name: 'article_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $article = $this->articleRepository->find($id);
        
        if (!$article) {
            return $this->json(
                ['error' => 'Статья не найдена'], 
                Response::HTTP_NOT_FOUND
            );
        }

        $this->articleRepository->remove($article, true);

        return $this->json([
            'message' => 'Статья успешно удалена'
        ]);
    }

    /**
     * Список статей
     */
    #[Route('', name: 'article_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $articles = $this->articleRepository->findAll();

        return $this->json(array_map([$this, 'serializeArticle'], $articles));
    }

    /**
     * Сериализация статьи для ответа
     */
    private function serializeArticle(Article $article): array
    {
        return [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'slug' => $article->getSlug(),
            'excerpt' => $article->getExcerpt(),
            'content' => $article->getContent(),
            'coverImage' => $article->getCoverImage(),
            'readingTime' => $article->getReadingTime(),
            'viewCount' => $article->getViewCount(),
            'isPublished' => $article->isPublished(),
            'isFeatured' => $article->isFeatured(),
            'publishedAt' => $article->getPublishedAt()?->format('Y-m-d H:i:s'),
            'createdAt' => $article->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $article->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'category' => $article->getCategory() ? [
                'id' => $article->getCategory()->getId(),
                'name' => $article->getCategory()->getName(),
                'slug' => $article->getCategory()->getSlug()
            ] : null,
            'tags' => array_map(function ($tag) {
                return [
                    'id' => $tag->getId(),
                    'name' => $tag->getName()
                ];
            }, $article->getTags()->toArray())
        ];
    }

    /**
     * Обработка ошибок из сервиса
     */
    private function handleErrorResult($result): JsonResponse
    {
        $errorData = [
            'success' => false,
            'error' => $result->getError()
        ];

        if ($result->getErrorDetails()) {
            $errorData['details'] = $result->getErrorDetails();
        }

        $statusCode = match($result->getError()) {
            'Article not found' => Response::HTTP_NOT_FOUND,
            'Статья с таким названием уже существует' => Response::HTTP_CONFLICT,
            'Validation failed' => Response::HTTP_BAD_REQUEST,
            default => Response::HTTP_BAD_REQUEST
        };

        return $this->json($errorData, $statusCode);
    }
}