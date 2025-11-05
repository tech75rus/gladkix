<?php

namespace App\Tag\Factory;

use App\Tag\Dto\CreateTagDto;
use App\Tag\Dto\UpdateTagDto;
use App\Tag\Entity\Tag;

class TagFactory
{
    public function createFromDto(CreateTagDto $dto): Tag
    {
        $tag = new Tag();

        $tag->setName($dto->name);
        $tag->setDescription($dto->description);

        return $tag;
    }

    public function updateFromDto(Tag $tag, UpdateTagDto $dto): void
    {
        if ($dto->name !== null) {
            $tag->setName($dto->name);
        }
        
        if ($dto->description !== null) {
            $tag->setDescription($dto->description);
        }        
    }
}