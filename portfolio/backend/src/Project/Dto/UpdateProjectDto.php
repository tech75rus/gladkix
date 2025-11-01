<?php

namespace App\Project\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateProjectDto
{
    public function __construct(
        #[Assert\Length(
            min: 3,
            max: 255,
            minMessage: "Название проекта должно содержать не меньше {{ limit }} символов",
            maxMessage: "Название проекта не может превышать {{ limit }} символов"
        )]
        public ?string $title = null,

        #[Assert\Length(
            min: 10,
            max: 1000,
            minMessage: "Краткое описание должно содержать не меньше {{ limit }} символов",
            maxMessage: "Краткое описание не может превышать {{ limit }} символов"
        )]
        public ?string $description = null,

        #[Assert\Length(
            min: 50,
            minMessage: "Подробное описание должно содержать не меньше {{ limit }} символов"
        )]
        public ?string $content = null,

        #[Assert\Url(message: "URL обложки должен быть валидным")]
        public ?string $coverImage = null,

        #[Assert\Url(message: "URL обложки должен быть валидным")]
        public ?string $projectUrl = null,

        #[Assert\Url(message: "URL GitHub репозитория должен быть валидным")]
        public ?string $githubUrl = null,

        #[Assert\Url(message: "URL демо должен быть валидным")]
        public ?string $demoUrl = null,

        #[Assert\Choice(
            choices: ['planning', 'in_progress', 'completed', 'on_hold', 'cancelled'],
            message: "Статус проекта должен быть: planning, in_progress, completed, on_hold или cancelled"
        )]
        public ?string $status = null,

        #[Assert\All([new Assert\Positive(message: "Все ID тегов должны быть положительными числами")])]
        public ?array $tagIds = null, // null = не обновлять, [] = удалить все теги
    ) {}
}