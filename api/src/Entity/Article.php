<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
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

    public function __construct()
    {
        $this->at_create = new \DateTime('now');
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
}
