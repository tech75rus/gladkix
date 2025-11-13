<?php

namespace App\Tag\Controller;

use App\Tag\Dto\UpdateTagDto;
use App\Tag\Entity\Tag;
use App\Tag\Factory\TagFactory;
use App\Tag\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Tag\Service\TagRequestHandler;

#[Route('/api/tags')]
class TagController extends AbstractController
{
    public function __construct(
        private TagRepository $tagRepository,
        private TagFactory $tagFactory,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private TagRequestHandler $tagRequestHandler
    ) {}

    /**
     * Создание тега
     */
    #[Route('', name: 'api_tag_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $result = $this->tagRequestHandler->handleCreateRequest($request);

        if ($result->isSuccess()) {
            return $this->json([
                'success' => true,
                'message' => 'Тэг успешно создан',
                'category' => $this->serializeTag($result->getTag())
            ], Response::HTTP_CREATED);
        }

        return $this->handleErrorResult($result);
    }

    /**
     * Получение тега по ID
     */
    #[Route('/{id}', name: 'api_tag_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $tag = $this->tagRepository->find($id);
        
        if (!$tag) {
            return $this->json(
                ['error' => 'Тег не найден'], 
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json([
            'tag' => $this->serializeTag($tag)
        ]);
    }

    /**
     * Обновление тега
     */
    #[Route('/{id}', name: 'api_tag_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            // 1. ПОИСК СУЩНОСТИ
            $tag = $this->tagRepository->find($id);
            if (!$tag) {
                return $this->json(
                    ['error' => 'Тег не найден'], 
                    Response::HTTP_NOT_FOUND
                );
            }

            // 2. ДЕСЕРИАЛИЗАЦИЯ JSON → DTO
            /** @var UpdateTagDto $updateDto */
            $updateDto = $this->serializer->deserialize(
                $request->getContent(),
                UpdateTagDto::class,
                'json'
            );

            // 3. ВАЛИДАЦИЯ DTO
            $errors = $this->validator->validate($updateDto);
            if (count($errors) > 0) {
                return $this->json(
                    ['errors' => (string)$errors], 
                    Response::HTTP_BAD_REQUEST
                );
            }

            // 4. ПРОВЕРКА УНИКАЛЬНОСТИ названия (если меняется)
            if ($updateDto->name !== null && $updateDto->name !== $tag->getName()) {
                $existingTag = $this->tagRepository->findOneBy(['name' => $updateDto->name]);
                if ($existingTag) {
                    return $this->json(
                        ['error' => 'Тег с таким названием уже существует'], 
                        Response::HTTP_BAD_REQUEST
                    );
                }
            }

            // 5. ОБНОВЛЕНИЕ СУЩНОСТИ через ФАБРИКУ
            $this->tagFactory->updateFromDto($tag, $updateDto);

            // 6. СОХРАНЕНИЕ ИЗМЕНЕНИЙ
            $this->tagRepository->save($tag, true);

            return $this->json([
                'message' => 'Тег успешно обновлен',
                'tag' => $this->serializeTag($tag)
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
     * Удаление тега
     */
    #[Route('/{id}', name: 'api_tag_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $tag = $this->tagRepository->find($id);
        
        if (!$tag) {
            return $this->json(
                ['error' => 'Тег не найден'], 
                Response::HTTP_NOT_FOUND
            );
        }

        // Проверяем есть ли связанные статьи или проекты
        $articleCount = $tag->getArticles()->count();
        $projectCount = $tag->getProjects()->count();

        if ($articleCount > 0 || $projectCount > 0) {
            return $this->json(
                [
                    'error' => 'Невозможно удалить тег с привязанными статьями или проектами',
                    'articleCount' => $articleCount,
                    'projectCount' => $projectCount
                ], 
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->tagRepository->remove($tag, true);

        return $this->json([
            'message' => 'Тег успешно удален'
        ]);
    }

    /**
     * Список тегов
     */
    #[Route('', name: 'api_tag_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $search = $request->query->get('search');
        
        if ($search) {
            // Поиск тегов по названию
            $tags = $this->tagRepository->searchByName($search);
        } else {
            // Все теги
            $tags = $this->tagRepository->findBy([], ['name' => 'ASC']);
        }

        return $this->json([
            'tags' => array_map([$this, 'serializeTagForList'], $tags)
        ]);
    }

    /**
     * Популярные теги (по количеству использований)
     */
    #[Route('/popular', name: 'api_tag_popular', methods: ['GET'])]
    public function popular(): JsonResponse
    {
        $tags = $this->tagRepository->findMostPopularTags(10);

        return $this->json([
            'tags' => array_map([$this, 'serializeTagForList'], $tags)
        ]);
    }

    /**
     * Сериализация тега для детального просмотра
     */
    private function serializeTag(Tag $tag): array
    {
        return [
            'id' => $tag->getId(),
            'name' => $tag->getName(),
            'description' => $tag->getDescription(),
            'createdAt' => $tag->getCreatedAt()?->format('Y-m-d H:i:s'),
            'articleCount' => $tag->getArticles()->count(),
            'projectCount' => $tag->getProjects()->count()
        ];
    }

    /**
     * Сериализация тега для списка
     */
    private function serializeTagForList(Tag $tag): array
    {
        return [
            'id' => $tag->getId(),
            'name' => $tag->getName(),
            'articleCount' => $tag->getArticles()->count(),
            'projectCount' => $tag->getProjects()->count()
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
            'Tag not found' => Response::HTTP_NOT_FOUND,
            'Тег с таким названием уже существует' => Response::HTTP_CONFLICT,
            'Validation failed' => Response::HTTP_BAD_REQUEST,
            default => Response::HTTP_BAD_REQUEST
        };

        return $this->json($errorData, $statusCode);
    }
}