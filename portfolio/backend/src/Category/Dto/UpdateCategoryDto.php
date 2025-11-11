<?php

namespace App\Category\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCategoryDto
{
    public function __construct(
        // Все поля опциональны для обновления
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: "Название категории должно содержать не менее {{ limit }} символов",
            maxMessage: "Название категории не может превышать {{ limit }} символов"
        )]
        public ?string $name = null,

        #[Assert\Length(
            max: 1000,
            maxMessage: "Описание не может превышать {{ limit }} символов"
        )]
        public ?string $description = null,

        #[Assert\Regex(
            pattern: '/^#[0-9A-Fa-f]{6}$/',
            message: "Цвет должен быть в HEX формате (#FFFFFF)"
        )]
        public ?string $color = null,

        #[Assert\Length(
            max: 50,
            maxMessage: "Название иконки не может превышать {{ limit }} символов"
        )]
        public ?string $icon = null,

        #[Assert\Url(message: "URL изображения должен быть валидным")]
        public ?string $image = null,

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

        #[Assert\Type(type: 'integer', message: "Порядок сортировки должен быть числом")]
        #[Assert\PositiveOrZero(message: "Порядок сортировки не может быть отрицательным")]
        public ?int $sortOrder = null,

        #[Assert\Type(type: 'bool', message: "Видимость должна быть true или false")]
        public ?bool $isVisible = null,
    ) {}
}