<?php

namespace App\Category\Entity;

use App\Article\Entity\Article;
use App\Category\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(
        length: 100, 
        unique: true,
        options: ['comment' => 'Название категории (Веб-разработка, Мобильные приложения, UI/UX)']
    )]
    private ?string $name = null;

    #[ORM\Column(
        length: 100,
        unique: true,
        options: ['comment' => 'URL-дружественное название категории (web-development, mobile-apps)']
    )]
    private ?string $slug = null;

    #[ORM\Column(
        type: Types::TEXT, 
        nullable: true,
        options: ['comment' => 'Подробное описание категории и что в нее входит']
    )]
    private ?string $description = null;

    #[ORM\Column(
        length: 7, 
        nullable: true,
        options: ['comment' => 'Цвет категории в формате HEX (например: #FF5733)']
    )]
    private ?string $color = null;

    #[ORM\Column(
        length: 50, 
        nullable: true,
        options: ['comment' => 'Название иконки из PrimeIcons (pi-desktop, pi-mobile, pi-palette)']
    )]
    private ?string $icon = null;

    #[ORM\Column(
        length: 500, 
        nullable: true,
        options: ['comment' => 'URL изображения категории (отображается в шапке категории)']
    )]
    private ?string $image = null;

    #[ORM\Column(
        length: 255, 
        nullable: true,
        options: ['comment' => 'SEO title для страницы категории']
    )]
    private ?string $metaTitle = null;

    #[ORM\Column(
        type: Types::TEXT, 
        nullable: true,
        options: ['comment' => 'SEO description для страницы категории']
    )]
    private ?string $metaDescription = null;

    #[ORM\Column(
        options: ['comment' => 'Порядок сортировки категорий (чем выше число, тем выше в списке)']
    )]
    private ?int $sortOrder = 0;

    #[ORM\Column(
        options: ['comment' => 'Видимость категории на сайте']
    )]
    private ?bool $isVisible = true;

    #[ORM\Column(
        options: ['comment' => 'Количество элементов (статей) в категории']
    )]
    private ?int $itemCount = 0;

    #[ORM\Column(
        options: ['comment' => 'Дата и время создания категории']
    )]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(
        nullable: true,
        options: ['comment' => 'Дата и время последнего обновления категории']
    )]
    private ?\DateTimeImmutable $updateAt = null;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\OneToMany(
        targetEntity: Article::class, 
        mappedBy: 'category'
    )]
    private Collection $Articles;

    public function __construct()
    {
        $this->Articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): static
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    public function getItemCount(): ?int
    {
        return $this->itemCount;
    }

    public function setItemCount(int $itemCount): static
    {
        $this->itemCount = $itemCount;

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
        return $this->updateAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updateAt = new \DateTimeImmutable();
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->Articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->Articles->contains($article)) {
            $this->Articles->add($article);
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->Articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }
}
