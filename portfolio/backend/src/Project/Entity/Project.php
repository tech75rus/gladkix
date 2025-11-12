<?php

namespace App\Project\Entity;

use App\Project\Repository\ProjectRepository;
use App\Tag\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(
        length: 255,
        unique: true,
        options: ['Название проекта']
    )]
    private ?string $title = null;

    #[ORM\Column(
        length: 255,
        unique: true,
        options: ['URL-дружественное название проекта']
    )]
    private ?string $slug = null;

    #[ORM\Column(
        type: Types::TEXT,
        options: ['Краткое описание проекта (показывается в списках)']
    )]
    private ?string $description = null;

    #[ORM\Column(
        type: Types::TEXT, 
        nullable: true,
        options: ['Подробное описание проекта: цели, технологии, результаты']
    )]
    private ?string $content = null;

    #[ORM\Column(
        length: 500, 
        nullable: true,
        options: ['URL основного изображения проекта (отображается в шапке проекта и в списках проектов)']
    )]
    private ?string $coverImage = null;

    #[ORM\Column(
        length: 500, 
        nullable: true,
        options: ['URL живой версии проекта (если доступен)']
    )]
    private ?string $projectUrl = null;

    #[ORM\Column(
        length: 500, 
        nullable: true,
        options: ['URL репозитория проекта на GitHub']
    )]
    private ?string $githubUrl = null;

    #[ORM\Column(
        length: 500, 
        nullable: true,
        options: ['URL демо-версии проекта (если доступна)']
    )]
    private ?string $demoUrl = null;

    #[ORM\Column(
        length: 20,
        options: ['Статус проекта: planning, in_progress, completed, on_hold, cancelled']
    )]
    private ?string $status = 'completed';

    #[ORM\Column(
        options: ['Дата и время создания проекта']
    )]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(
        nullable: true,
        options: ['Дата и время последнего обновления проекта']
    )]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'projects')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getProjectUrl(): ?string
    {
        return $this->projectUrl;
    }

    public function setProjectUrl(?string $projectUrl): static
    {
        $this->projectUrl = $projectUrl;

        return $this;
    }

    public function getGithubUrl(): ?string
    {
        return $this->githubUrl;
    }

    public function setGithubUrl(?string $githubUrl): static
    {
        $this->githubUrl = $githubUrl;

        return $this;
    }

    public function getDemoUrl(): ?string
    {
        return $this->demoUrl;
    }

    public function setDemoUrl(?string $demoUrl): static
    {
        $this->demoUrl = $demoUrl;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
