<?php

namespace App\Category\Service;

use App\Category\Dto\CreateCategoryDto;
use App\Category\Dto\UpdateCategoryDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryRequestHandler
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private CategoryService $categoryService
    ) {}

    /**
     * Обработка запроса на создание категории
     */
    public function handleCreateRequest(Request $request): CategoryCreationResult
    {
        try {
            /** @var CreateCategoryDto $createDto */
            $createDto = $this->serializer->deserialize(
                $request->getContent(),
                CreateCategoryDto::class,
                'json'
            );

            // Валидация DTO
            $errors = $this->validator->validate($createDto);
            if (count($errors) > 0) {
                return CategoryCreationResult::validationFailed($errors);
            }

            // Делегируем создание в основной сервис
            return $this->categoryService->createCategory($createDto);

        } catch (MissingConstructorArgumentsException $e) {
            return CategoryCreationResult::missingFields(
                $e->getMissingConstructorArguments(),
                ['name'] // обязательные поля
            );
        } catch (\InvalidArgumentException $e) {
            return CategoryCreationResult::invalidArgument($e->getMessage());
        } catch (\Exception $e) {
            return CategoryCreationResult::serverError($e->getMessage());
        }
    }

    /**
     * Обработка запроса на обновление категории
     */
    public function handleUpdateRequest(int $id, Request $request): CategoryUpdateResult
    {
        try {
            /** @var UpdateCategoryDto $updateDto */
            $updateDto = $this->serializer->deserialize(
                $request->getContent(),
                UpdateCategoryDto::class,
                'json'
            );
            
            // Валидация DTO
            $errors = $this->validator->validate($updateDto);
            if (count($errors) > 0) {
                return CategoryUpdateResult::validationFailed($errors);
            }
            
            return $this->categoryService->updateCategory($id, $updateDto);
            
        } catch (MissingConstructorArgumentsException $e) {
            return CategoryUpdateResult::missingFields($e->getMissingConstructorArguments());
        } catch (\Exception $e) {
            return CategoryUpdateResult::serverError($e->getMessage());
        }
    }
}