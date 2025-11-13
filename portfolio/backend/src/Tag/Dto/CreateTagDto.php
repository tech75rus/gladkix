<?php

namespace App\Tag\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTagDto
{
    public function __construct(
        #[Assert\NotBlank(message: "Название тега обязательно")]
        #[Assert\Length(
            min: 2,
            max: 50,
            minMessage: "Название тега должно содержать не менее {{ limit }} символов",
            maxMessage: "Название проекта не может превышать {{ limit }} символов"
        )]
        #[Assert\Regex(
            pattern: '/^[a-zA-Zа-яА-Я0-9\s\.\+\#\-\_]+$/u',
            message: "Название тега может содержать только буквы, цифры, пробелы и символы .+#-_"
        )]
        public string $name,

        #[Assert\Length(
            min: 2,
            max: 1000,
            minMessage: "Краткое описание должно содержать не меньше {{ limit }} символов",
            maxMessage: "Краткое описание не может превышать {{ limit }} символов"
        )]
        public ?string $description = null,
    ) {}
}