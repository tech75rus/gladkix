<?php
// src/Category/Service/CategoryService.php

namespace App\Category\Service;

use App\Category\Dto\CreateCategoryDto;
use App\Category\Dto\UpdateCategoryDto;
use App\Category\Entity\Category;
use App\Category\Factory\CategoryFactory;
use App\Category\Repository\CategoryRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private CategoryFactory $categoryFactory,
        private ValidatorInterface $validator
    ) {}

    /**
     * Создание категории с полной валидацией
     */
    public function createCategory(CreateCategoryDto $dto): CategoryCreationResult
    {
        // Проверка уникальности имени
        $existingCategory = $this->categoryRepository->findOneBy(['name' => $dto->name]);
        if ($existingCategory) {
            return CategoryCreationResult::duplicateName($dto->name, $existingCategory);
        }

        try {
            // Создание и сохранение
            $category = $this->categoryFactory->createFromDto($dto);
            $this->categoryRepository->save($category, true);

            return CategoryCreationResult::success($category);

        } catch (UniqueConstraintViolationException $e) {
            return CategoryCreationResult::databaseConstraintViolation($e);
        }
    }

    /**
     * Обновление категории
     */
    public function updateCategory(int $id, UpdateCategoryDto $dto): CategoryUpdateResult
    {
        /** @var Category $category */
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return CategoryUpdateResult::notFound();
        }

        // Валидация DTO
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return CategoryUpdateResult::validationFailed($errors);
        }

        // Проверка уникальности имени (если меняется)
        if ($dto->name !== null && $dto->name !== $category->getName()) {
            $existingCategory = $this->categoryRepository->findOneBy(['name' => $dto->name]);
            if ($existingCategory && $existingCategory->getId() !== $category->getId()) {
                return CategoryUpdateResult::duplicateName($dto->name);
            }
        }

        // Обновление и сохранение
        $this->categoryFactory->updateFromDto($category, $dto);
        $this->categoryRepository->save($category, true);

        return CategoryUpdateResult::success($category);
    }
}