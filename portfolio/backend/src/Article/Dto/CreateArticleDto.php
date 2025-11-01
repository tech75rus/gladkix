<?php

namespace App\Article\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateArticleDto
{
    public function __construct(
        #[Assert\NotBlank(message: "Заголовок статьи обязателен")]
        #[Assert\Length(
            min: 5,
            max: 255,
            minMessage: "Заголовок должен содержать минимум {{ limit }} символов",
            maxMessage: "Заголовок не может превышать {{ limit }} символов"
        )]
        public string $title,

        #[Assert\NotBlank(message: "Содержание статьи обязательно")]
        #[Assert\Length(
            min: 100,
            minMessage: "Статья должна содержать больше {{ limit }} символов"
        )]
        public string $content,

        #[Assert\Length(
            max: 500,
            maxMessage: "Краткое описание не может превышать {{ limit }} символов"
        )]
        public ?string $excerpt = null,

        #[Assert\Url(message: "URL обложки должен быть валидным")]
        public ?string $coverImage = null,

        #[Assert\Positive(message: "ID категории должен быть положительным числом")]
        public ?int $categoryId = null,

        #[Assert\All([
            new Assert\Positive(message: "Все ID тегов должны быть положительными числами")
        ])]
        public array $tagIds = [],

        #[Assert\Type(type: 'bool', message: "Статус публикации должен быть true или false")]
        public bool $isPublished = false,

        #[Assert\Type(type: 'bool', message: "Рекомендованная статья должна быть true или false")]
        public bool $isFeatured = false,

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