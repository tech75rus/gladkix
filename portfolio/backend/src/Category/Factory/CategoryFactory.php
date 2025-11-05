<?php

namespace App\Category\Factory;

use App\Category\Dto\CreateCategoryDto;
use App\Category\Dto\UpdateCategoryDto;
use App\Category\Entity\Category;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFactory
{
    public function __construct(
        private SluggerInterface $slugger
    ) {}

    public function createFromDto(CreateCategoryDto $dto): Category
    {
        $category = new Category();
        
        $category->setName($dto->name);
        $category->setSlug($this->generateSlug($dto->name));
        $category->setDescription($dto->description);
        $category->setColor($dto->color);
        $category->setIcon($dto->icon);
        $category->setImage($dto->image);
        $category->setMetaTitle($dto->metaTitle);
        $category->setMetaDescription($dto->metaDescription);
        $category->setSortOrder($dto->sortOrder);
        $category->setIsVisible($dto->isVisible);
        
        return $category;
    }

    public function updateFromDto(Category $category, UpdateCategoryDto $dto): void
    {
        if ($dto->name !== null) {
            $category->setName($dto->name);
            $category->setSlug($this->generateSlug($dto->name));
        }
        
        if ($dto->description !== null) {
            $category->setDescription($dto->description);
        }
        
        if ($dto->color !== null) {
            $category->setColor($dto->color);
        }
        
        if ($dto->icon !== null) {
            $category->setIcon($dto->icon);
        }
        
        if ($dto->image !== null) {
            $category->setImage($dto->image);
        }
        
        if ($dto->metaTitle !== null) {
            $category->setMetaTitle($dto->metaTitle);
        }
        
        if ($dto->metaDescription !== null) {
            $category->setMetaDescription($dto->metaDescription);
        }
        
        if ($dto->sortOrder !== null) {
            $category->setSortOrder($dto->sortOrder);
        }
        
        if ($dto->isVisible !== null) {
            $category->setIsVisible($dto->isVisible);
        }        
    }

    private function generateSlug(string $name): string
    {
        return $this->slugger->slug($name)->lower();
    }
}