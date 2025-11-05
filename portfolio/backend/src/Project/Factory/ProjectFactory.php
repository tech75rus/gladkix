<?php
// src/Project/Factory/ProjectFactory.php

namespace App\Project\Factory;

use App\Project\Dto\CreateProjectDto;
use App\Project\Dto\UpdateProjectDto;
use App\Project\Entity\Project;
use App\Tag\Entity\Tag;
use App\Tag\Repository\TagRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProjectFactory
{
    public function __construct(
        private SluggerInterface $slugger,
        private TagRepository $tagRepository
    ) {}

    public function createFromDto(CreateProjectDto $dto): Project
    {
        $project = new Project();

        $project->setTitle($dto->title);
        $project->setSlug($this->generateSlug($dto->title));
        $project->setDescription($dto->description);
        $project->setContent($dto->content);
        $project->setCoverImage($dto->coverImage);
        $project->setProjectUrl($dto->projectUrl);
        $project->setGithubUrl($dto->githubUrl);
        $project->setDemoUrl($dto->demoUrl);
        $project->setStatus($dto->status);

        $this->addTagsToProject($project, $dto->tagIds);

        return $project;
    }

    public function updateFromDto(Project $project, UpdateProjectDto $dto): void
    {
        if ($dto->title !== null) {
            $project->setTitle($dto->title);
            $project->setSlug($this->generateSlug($dto->title));
        }
        
        if ($dto->description !== null) {
            $project->setDescription($dto->description);
        }
        
        if ($dto->content !== null) {
            $project->setContent($dto->content);
        }
        
        if ($dto->coverImage !== null) {
            $project->setCoverImage($dto->coverImage);
        }
        
        if ($dto->projectUrl !== null) {
            $project->setProjectUrl($dto->projectUrl);
        }
        
        if ($dto->githubUrl !== null) {
            $project->setGithubUrl($dto->githubUrl);
        }
        
        if ($dto->demoUrl !== null) {
            $project->setDemoUrl($dto->demoUrl);
        }
        
        if ($dto->status !== null) {
            $project->setStatus($dto->status);
        }

        if ($dto->tagIds !== null) {
            $this->updateProjectTags($project, $dto->tagIds);
        }
    }

    private function generateSlug(string $title): string
    {
        return $this->slugger->slug($title)->lower();
    }

    private function addTagsToProject(Project $project, array $tagIds): void
    {
        foreach ($tagIds as $tagId) {
            $tag = $this->tagRepository->find($tagId);
            if ($tag) {
                $project->addTag($tag);
            }
        }
    }

    private function updateProjectTags(Project $project, array $newTagIds): void
    {
        // Удаляем текущие теги
        foreach ($project->getTags() as $tag) {
            $project->removeTag($tag);
        }
        
        // Добавляем новые теги
        $this->addTagsToProject($project, $newTagIds);
    }
}