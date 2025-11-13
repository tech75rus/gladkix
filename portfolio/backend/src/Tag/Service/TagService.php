<?php

namespace App\Tag\Service;

use App\Tag\Dto\CreateTagDto;
use App\Tag\Dto\UpdateTagDto;
use App\Tag\Entity\Tag;
use App\Tag\Factory\TagFactory;
use App\Tag\Repository\TagRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TagService
{
    public function __construct(
        private TagRepository $tagRepository,
        private TagFactory $tagFactory,
        private ValidatorInterface $validator
    ) {}

    /**
     * Создание тега с полной валидацией
     */
    public function createTag(CreateTagDto $dto): TagCreationResult
    {
        // Проверка уникальности имени
        $existingTag = $this->tagRepository->findOneBy(['name' => $dto->name]);
        if ($existingTag) {
            return TagCreationResult::duplicateName($dto->name, $existingTag);
        }

        try {
            // Создание и сохранение
            $tag = $this->tagFactory->createFromDto($dto);
            $this->tagRepository->save($tag, true);

            return TagCreationResult::success($tag);

        } catch (UniqueConstraintViolationException $e) {
            return TagCreationResult::databaseConstraintViolation($e);
        }
    }

    /**
     * Обновление тега
     */
    public function updateTag(int $id, UpdateTagDto $dto): TagUpdateResult
    {
        /** @var Tag $tag */
        $tag = $this->tagRepository->find($id);
        if (!$tag) {
            return TagUpdateResult::notFound();
        }

        // Валидация DTO
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return TagUpdateResult::validationFailed($errors);
        }

        // Проверка уникальности заголовка (если меняется)
        if ($dto->name !== null && $dto->name !== $tag->getName()) {
            $existingTag = $this->tagRepository->findOneBy(['name' => $dto->name]);
            if ($existingTag && $existingTag->getId() !== $tag->getId()) {
                return TagUpdateResult::duplicateName($dto->name);
            }
        }

        // Обновление и сохранение
        $this->tagFactory->updateFromDto($tag, $dto);
        $this->tagRepository->save($tag, true);

        return TagUpdateResult::success($tag);
    }
}