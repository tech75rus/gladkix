<?php

namespace App\Article\Factory;

use App\Article\Dto\CreateArticleDto;
use App\Article\Dto\UpdateArticleDto;
use App\Article\Entity\Article;
use App\Category\Entity\Category;
use App\Category\Repository\CategoryRepository;
use App\Tag\Entity\Tag;
use App\Tag\Repository\TagRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleFactory
{
    public function __construct(
        private SluggerInterface $slugger,
        private CategoryRepository $categoryRepository,
        private TagRepository $tagRepository
    ) {}

    public function createFromDto(CreateArticleDto $dto): Article
    {
        $article = new Article();

        $article->setTitle($dto->title);
        $article->setSlug($this->slugger->slug($dto->title)->lower());
        $article->setExcerpt($dto->excerpt);
        $article->setContent($dto->content);
        $article->setCoverImage($dto->coverImage);
        $article->setReadingTime($dto->readingTime);
        $article->setIsPublished($dto->isPublished);
        $article->setIsFeatured($dto->isFeatured);
        $article->setMetaTitle($dto->metaTitle);
        $article->setMetaDescription($dto->metaDescription);

        $category = $this->categoryRepository->find($dto->categoryId);
        if (!$category) {
            throw new \InvalidArgumentException("Категория с ID {$dto->categoryId} не найдена");
        }
        $article->setCategory($category);

        foreach ($dto->tagIds as $tagId) {
            $tag = $this->tagRepository->find($tagId);
            if ($tag) {
                $article->addTag($tag);
            }
        }

        if ($dto->isPublished && !$dto->publishedAt) {
            $article->setPublishedAt(new \DateTimeImmutable());
        } elseif ($dto->publishedAt) {
            $article->setPublishedAt($dto->publishedAt);
        }
        
        // created_at и updated_at установятся автоматически
        
        return $article;
    }

    public function updateFromDto(Article $article, UpdateArticleDto $dto): void
    {
        if ($dto->title !== null) {
            $article->setTitle($dto->title);
            $article->setSlug($this->slugger->slug($dto->title)->lower());
        }
        
        if ($dto->excerpt !== null) {
            $article->setExcerpt($dto->excerpt);
        }
        
        if ($dto->content !== null) {
            $article->setContent($dto->content);
        }
        
        if ($dto->coverImage !== null) {
            $article->setCoverImage($dto->coverImage);
        }
        
        if ($dto->readingTime !== null) {
            $article->setReadingTime($dto->readingTime);
        }
        
        if ($dto->isPublished !== null) {
            $article->setIsPublished($dto->isPublished);
            // Если статья публикуется впервые - устанавливаем дату публикации
            if ($dto->isPublished && !$article->getPublishedAt()) {
                $article->setPublishedAt(new \DateTimeImmutable());
            }
        }
        
        if ($dto->isFeatured !== null) {
            $article->setIsFeatured($dto->isFeatured);
        }
        
        if ($dto->metaTitle !== null) {
            $article->setMetaTitle($dto->metaTitle);
        }
        
        if ($dto->metaDescription !== null) {
            $article->setMetaDescription($dto->metaDescription);
        }
        
        if ($dto->publishedAt !== null) {
            $article->setPublishedAt($dto->publishedAt);
        }
        
        // ✅ ОБНОВЛЕНИЕ КАТЕГОРИИ
        if ($dto->categoryId !== null) {
            $category = $this->categoryRepository->find($dto->categoryId);
            if (!$category) {
                throw new \InvalidArgumentException("Категория с ID {$dto->categoryId} не найдена");
            }
            $article->setCategory($category);
        }
        
        // ✅ ОБНОВЛЕНИЕ ТЕГОВ
        if ($dto->tagIds !== null) {
            // Очищаем текущие теги
            foreach ($article->getTags() as $tag) {
                $article->removeTag($tag);
            }
            
            // Добавляем новые теги
            foreach ($dto->tagIds as $tagId) {
                $tag = $this->tagRepository->find($tagId);
                if ($tag) {
                    $article->addTag($tag);
                }
            }
        }
        
        // updated_at обновится автоматически через @ORM\PreUpdate
    }

    /**
     * Создает статью с минимальными обязательными полями
     */
    public function createBasicArticle(string $title, string $content, int $categoryId): Article
    {
        $dto = new CreateArticleDto(
            title: $title,
            excerpt: substr(strip_tags($content), 0, 200) . '...',
            content: $content,
            categoryId: $categoryId,
            tagIds: [],
            readingTime: $this->calculateReadingTime($content),
            isPublished: false,
            isFeatured: false
        );
        
        return $this->createFromDto($dto);
    }

    /**
     * Рассчитывает время чтения статьи (примерно 200 слов в минуту)
     */
    private function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        $readingTime = ceil($wordCount / 200);
        
        return max(1, (int)$readingTime); // Минимум 1 минута
    }
}