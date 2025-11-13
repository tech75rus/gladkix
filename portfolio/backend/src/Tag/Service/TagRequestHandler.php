<?php

namespace App\Tag\Service;

use App\Tag\Dto\CreateTagDto;
use App\Tag\Dto\UpdateTagDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Tag\Service\TagService;

class TagRequestHandler
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private TagService $tagService
    ) {}

    /**
     * Обработка запроса на создание проекта
     */
    public function handleCreateRequest(Request $request): TagCreationResult
    {
        try {
            /** @var CreateTagDto $createDto */
            $createDto = $this->serializer->deserialize(
                $request->getContent(),
                CreateTagDto::class,
                'json'
            );

            // Валидация DTO
            $errors = $this->validator->validate($createDto);
            if (count($errors) > 0) {
                return TagCreationResult::validationFailed($errors);
            }

            // Делегируем создание в основной сервис
            return $this->tagService->createTag($createDto);

        } catch (MissingConstructorArgumentsException $e) {
            return TagCreationResult::missingFields(
                $e->getMissingConstructorArguments(),
                ['name'] // обязательные поля
            );
        } catch (\InvalidArgumentException $e) {
            return TagCreationResult::invalidArgument($e->getMessage());
        } catch (\Exception $e) {
            return TagCreationResult::serverError($e->getMessage());
        }
    }

    /**
     * Обработка запроса на обновление проекта
     */
    public function handleUpdateRequest(int $id, Request $request): TagUpdateResult
    {
        try {
            /** @var UpdateTagDto $updateDto */
            $updateDto = $this->serializer->deserialize(
                $request->getContent(),
                UpdateTagDto::class,
                'json'
            );
            
            // Валидация DTO
            $errors = $this->validator->validate($updateDto);
            if (count($errors) > 0) {
                return TagUpdateResult::validationFailed($errors);
            }
            
            return $this->tagService->updateTag($id, $updateDto);
            
        } catch (MissingConstructorArgumentsException $e) {
            return TagUpdateResult::missingFields($e->getMissingConstructorArguments());
        } catch (\Exception $e) {
            return TagUpdateResult::serverError($e->getMessage());
        }
    }
}