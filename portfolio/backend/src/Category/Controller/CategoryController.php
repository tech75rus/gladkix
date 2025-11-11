<?php
// src/Category/Controller/CategoryController.php

namespace App\Category\Controller;

use App\Category\Dto\UpdateCategoryDto;
use App\Category\Entity\Category;
use App\Category\Factory\CategoryFactory;
use App\Category\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Category\Service\CategoryService;
use App\Category\Service\CategoryRequestHandler;

#[AutoconfigureTag('controller.service_arguments')]
#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private CategoryFactory $categoryFactory,
        private CategoryService $categoryService,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private CategoryRequestHandler $categoryRequestHandler
    ) {}

    /**
     * Создание категории
     */
    #[Route('', name: 'category_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $result = $this->categoryRequestHandler->handleCreateRequest($request);

        if ($result->isSuccess()) {
            return $this->json([
                'success' => true,
                'message' => 'Категория успешно создана',
                'category' => $this->serializeCategory($result->getCategory())
            ], Response::HTTP_CREATED);
        }

        return $this->handleErrorResult($result);
    }

    /**
     * Получение категории по ID
     */
    #[Route('/{id}', name: 'category_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            return $this->json(
                ['error' => 'Категория не найдена'], 
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json([
            'category' => $this->serializeCategory($category)
        ]);
    }

    /**
     * Получение категории по slug
     */
    #[Route('/slug/{slug}', name: 'category_show_by_slug', methods: ['GET'])]
    public function showBySlug(string $slug): JsonResponse
    {
        $category = $this->categoryRepository->findOneBy(['slug' => $slug]);
        
        if (!$category) {
            return $this->json(
                ['error' => 'Категория не найдена'], 
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json([
            'category' => $this->serializeCategory($category)
        ]);
    }

    /**
     * Обновление категории
     */
    #[Route('/{id}', name: 'category_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            /** @var UpdateCategoryDto $updateDto */
            $updateDto = $this->serializer->deserialize(
                $request->getContent(),
                UpdateCategoryDto::class,
                'json'
            );

            $result = $this->categoryService->updateCategory($id, $updateDto);

            if ($result->isSuccess()) {
                return $this->json([
                    'success' => true,
                    'message' => 'Категория успешно обновлена',
                    'category' => $this->serializeCategory($result->getCategory())
                ]);
            }

            return $this->handleErrorResult($result);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Внутренняя ошибка сервера'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Удаление категории
     */
    #[Route('/{id}', name: 'category_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            return $this->json(
                ['error' => 'Категория не найдена'], 
                Response::HTTP_NOT_FOUND
            );
        }

        // Проверяем есть ли связанные статьи
        if ($category->getItemCount() > 0) {
            return $this->json(
                ['error' => 'Невозможно удалить категорию с привязанными статьями'], 
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->categoryRepository->remove($category, true);

        return $this->json([
            'message' => 'Категория успешно удалена'
        ]);
    }

    /**
     * Список категорий
     */
    #[Route('', name: 'category_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $onlyVisible = $request->query->get('visible', 'true') === 'true';
        
        if ($onlyVisible) {
            $categories = $this->categoryRepository->findBy(
                ['isVisible' => true], 
                ['sortOrder' => 'ASC', 'name' => 'ASC']
            );
        } else {
            $categories = $this->categoryRepository->findBy(
                [], 
                ['sortOrder' => 'ASC', 'name' => 'ASC']
            );
        }

        return $this->json([
            'categories' => array_map([$this, 'serializeCategoryForList'], $categories)
        ]);
    }

    /**
     * Сериализация категории для детального просмотра
     */
    private function serializeCategory(Category $category): array
    {
        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'description' => $category->getDescription(),
            'color' => $category->getColor(),
            'icon' => $category->getIcon(),
            'image' => $category->getImage(),
            'metaTitle' => $category->getMetaTitle(),
            'metaDescription' => $category->getMetaDescription(),
            'sortOrder' => $category->getSortOrder(),
            'isVisible' => $category->isVisible(),
            'itemCount' => $category->getItemCount(),
            'createdAt' => $category->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $category->getUpdatedAt()?->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Сериализация категории для списка
     */
    private function serializeCategoryForList(Category $category): array
    {
        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'color' => $category->getColor(),
            'icon' => $category->getIcon(),
            'sortOrder' => $category->getSortOrder(),
            'isVisible' => $category->isVisible(),
            'itemCount' => $category->getItemCount()
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
            'Category not found' => Response::HTTP_NOT_FOUND,
            'Категория с таким названием уже существует' => Response::HTTP_CONFLICT,
            'Validation failed' => Response::HTTP_BAD_REQUEST,
            default => Response::HTTP_BAD_REQUEST
        };

        return $this->json($errorData, $statusCode);
    }
}