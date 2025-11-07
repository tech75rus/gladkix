<?php

namespace App\Article\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateArticleDto
{
    public function __construct(
        #[Assert\Length(
            min: 5,
            max: 255,
            minMessage: "Заголовок должен содержать минимум {{ limit }} символов",
            maxMessage: "Заголовок не может превышать {{ limit }} символов"
        )]
        public ?string $title = null,

        #[Assert\Length(
            min: 100,
            minMessage: "Статья должна содержать больше {{ limit }} символов"
        )]
        public ?string $content = null,

        #[Assert\Length(
            max: 500,
            maxMessage: "Краткое описание не может превышать {{ limit }} символов"
        )]
        public ?string $excerpt = null,

        #[Assert\Url(message: "URL обложки должен быть валидным")]
        public ?string $coverImage = null,

        #[Assert\Positive(message: "ID категории должен быть положительным числом")]
        public ?int $categoryId = null,

        #[Assert\All(
            new Assert\Positive(message: "Все ID тегов должны быть положительными числами")
        )]
        public ?array $tagIds = null, // null = не обновлять, [] = удалить все теги

        #[Assert\Type(type: 'bool', message: "Статус публикации должен быть true или false")]
        public ?bool $isPublished = null,

        #[Assert\Type(type: 'bool', message: "Рекомендованная статья должна быть true или false")]
        public ?bool $isFeatured = null,

        #[Assert\DateTime(message: "Дата публикации должна быть в формате Y-m-d H:i:s")]
        public ?\DateTimeImmutable $publishedAt = null,

        #[Assert\Length(
            max: 255,
            maxMessage: "SEO заголовок не может превышать {{ limit }} символов"
        )]
        public ?string $metaTitle = null,

        #[Assert\Length(
            max: 500,
            maxMessage: "SEO описание не может превышать {{ limit }} символов"
        )]
        public ?string $metaDescription = null,
    ) {}
}