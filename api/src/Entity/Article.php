<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('app')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('app')]
    private ?string $header = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('app')]
    private ?string $article = null;

    #[Groups('app')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $at_create = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $at_update = null;

    #[ORM\Column(length: 1024)]
    #[Groups('app')]
    private ?string $short_article = null;

    #[ORM\ManyToMany(targetEntity: TechnologyTag::class, mappedBy: 'articles')]
    private Collection $technologyTags;

    public function __construct()
    {
        $this->at_create = new \DateTime('now');
        $this->technologyTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function setHeader(string $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getAtCreate(): ?\DateTimeInterface
    {
        return $this->at_create;
    }

    public function getAtUpdate(): ?\DateTimeInterface
    {
        return $this->at_update;
    }

    public function setAtUpdate(?\DateTimeInterface $at_update): self
    {
        $this->at_update = $at_update;

        return $this;
    }

    public function getShortArticle(): ?string
    {
        return $this->short_article;
    }

    public function setShortArticle(string $short_article): self
    {
        $this->short_article = $short_article;

        return $this;
    }

    /**
     * @return Collection<int, TechnologyTag>
     */
    public function getTechnologyTags(): Collection
    {
        return $this->technologyTags;
    }

    public function addTechnologyTag(TechnologyTag $technologyTag): self
    {
        if (!$this->technologyTags->contains($technologyTag)) {
            $this->technologyTags->add($technologyTag);
            $technologyTag->addArticle($this);
        }

        return $this;
    }

    public function removeTechnologyTag(TechnologyTag $technologyTag): self
    {
        if ($this->technologyTags->removeElement($technologyTag)) {
            $technologyTag->removeArticle($this);
        }

        return $this;
    }
}
