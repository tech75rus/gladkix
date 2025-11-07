<?php

namespace App\Article\Controller;

use App\Article\Dto\CreateArticleDto;
use App\Article\Dto\UpdateArticleDto;
use App\Article\Entity\Article;
use App\Article\Factory\ArticleFactory;
use App\Article\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/articles')]
class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ArticleFactory $articleFactory,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    /**
     * Создание статьи
     */
    #[Route('', name: 'article_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            /** @var CreateArticleDto $createDto */
            $createDto = $this->serializer->deserialize(
                $request->getContent(),
                CreateArticleDto::class,
                'json'
            );
            $errors = $this->validator->validate($createDto);
            if (count($errors) > 0) {
                $errorMessages = [];
                
                foreach ($errors as $error) {
                    $fieldName = $error->getPropertyPath();
                    $message = $error->getMessage();
                    
                    $errorMessages[$fieldName] = $message;
                }
                
                return $this->json([
                    'success' => false,
                    'message' => 'Ошибки валидации',
                    'errors' => $errorMessages
                ], Response::HTTP_BAD_REQUEST);
            }
            
            $article = $this->articleFactory->createFromDto($createDto);

            $this->articleRepository->save($article, true);

            return $this->json([
                'message' => 'Статья успешно создана',
                'article' => $this->serializeArticle($article)
            ], Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return $this->json(
                ['error' => $e->getMessage()], 
                Response::HTTP_BAD_REQUEST
            );
        } catch(MissingConstructorArgumentsException $e) {
            $missingFields = $e->getMissingConstructorArguments();
            
            return $this->json([
                'success' => false,
                'message' => 'Отсутствуют обязательные поля',
                'missingFields' => $missingFields,
                'requiredFields' => [
                    'title',
                    'content'
                ]
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            dd($e);
            return $this->json(
                ['error' => 'Внутренняя ошибка сервера'], 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
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

        return $this->json([
            'article' => $this->serializeArticle($article)
        ]);
    }

    /**
     * Обновление статьи
     */
    #[Route('/{id}', name: 'article_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $article = $this->articleRepository->find($id);
            if (!$article) {
                return $this->json(
                    ['error' => 'Статья не найдена'], 
                    Response::HTTP_NOT_FOUND
                );
            }

            /** @var UpdateArticleDto $updateDto */
            $updateDto = $this->serializer->deserialize(
                $request->getContent(),
                UpdateArticleDto::class,
                'json'
            );

            $errors = $this->validator->validate($updateDto);
            if (count($errors) > 0) {
                return $this->json(
                    ['errors' => (string)$errors], 
                    Response::HTTP_BAD_REQUEST
                );
            }

            $this->articleFactory->updateFromDto($article, $updateDto);

            $this->articleRepository->save($article, true);

            return $this->json([
                'message' => 'Статья успешно обновлена',
                'article' => $this->serializeArticle($article)
            ]);

        } catch (\InvalidArgumentException $e) {
            return $this->json(
                ['error' => $e->getMessage()], 
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Внутренняя ошибка сервера'], 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
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

        return $this->json([
            'articles' => array_map([$this, 'serializeArticle'], $articles)
        ]);
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
}