<?php

namespace App\Article\Entity;

use App\Article\Repository\ArticleRepository;
use App\Category\Entity\Category;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(
        length: 255, 
        unique: true,
        options: ['comment' => 'Заголовок статьи (отображается пользователям)']
    )]
    private ?string $title = null;

    #[ORM\Column(
        length: 255, 
        unique: true,
        options: ['comment' => 'URL-дружественное название статьи (например: \'moja-pervaja-statja\')']
    )]
    private ?string $slug = null;

    #[ORM\Column(
        type: Types::TEXT, 
        nullable: true,
        options: ['comment' => 'Краткое описание статьи (отображается в списках статей)']
    )]
    private ?string $excerpt = null;

    #[ORM\Column(
        type: Types::TEXT,
        options: ['comment' => 'Полное содержание статьи в формате HTML или Markdown']
    )]
    private ?string $content = null;

    #[ORM\Column(
        type: Types::TEXT, 
        length: 500, 
        nullable: true,
        options: ['comment' => 'URL основного изображения статьи (отображается в шапке статьи и в списках статей)']
    )]
    private ?string $coverImage = null;

    #[ORM\Column(
        options: ['comment' => 'Оценочное время чтения статьи в минутах']
    )]
    private ?int $readingTime = 5;

    #[ORM\Column(
        options: ['comment' => 'Количество просмотров статьи']
    )]
    private ?int $viewCount = 0;

    #[ORM\Column(
        options: ['comment' => 'Статус публикации статьи (опубликована или нет)']
    )]
    private ?bool $isPublished = false;

    #[ORM\Column(
        options: ['comment' => 'Является ли статья featured/рекомендованной']
    )]
    private ?bool $isFeatured = false;

    #[ORM\Column(
        length: 255, 
        nullable: true,
        options: ['comment' => 'Мета-заголовок для SEO целей']
    )]
    private ?string $metaTitle = null;

    #[ORM\Column(
        type: Types::TEXT, 
        nullable: true,
        options: ['comment' => 'Мета-описание для SEO целей']
    )]
    private ?string $metaDescription = null;

    #[ORM\Column(
        nullable: true,
        options: ['comment' => 'Дата и время публикации статьи']
    )]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column(
        options: ['comment' => 'Дата и время создания статьи']
    )]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(
        nullable: true,
        options: ['comment' => 'Дата и время последнего обновления статьи']
    )]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\ManyToOne(inversedBy: 'Articles')]
    private ?Category $category = null;

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

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(?string $excerpt): static
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getReadingTime(): ?int
    {
        return $this->readingTime;
    }

    public function setReadingTime(int $readingTime): static
    {
        $this->readingTime = $readingTime;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): static
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function isFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): static
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): static
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): static
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    #[ORM\PreUpdate]
    public function setUpdateAt(?\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
