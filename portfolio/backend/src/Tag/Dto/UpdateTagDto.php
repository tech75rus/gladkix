<?php

namespace App\Tag\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateTagDto
{
    public function __construct(
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
        public ?string $name = null,

        #[Assert\Length(max: 1000)]
        public ?string $description = null,
    ) {}
}