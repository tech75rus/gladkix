<?php

// src/DataFixtures/ArticleFixtures.php

namespace App\DataFixtures;

use App\Article\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ArticleFixtures extends Fixture
{
    private Generator $faker;
    private AsciiSlugger $slugger;

    public function __construct()
    {
        $this->faker = Factory::create('ru_RU');
        $this->slugger = new AsciiSlugger();
    }

    public function load(ObjectManager $manager): void
    {
        // Создаём 20 статей
        for ($i = 1; $i <= 20; $i++) {
            $article = $this->createArticle($i);
            $manager->persist($article);
            
            // Сохраняем ссылку для использования в других фикстурах
            $this->addReference('article_' . $i, $article);
        }
        
        $manager->flush();
    }
    
    private function createArticle(int $index): Article
    {
        $article = new Article();

        // Генерируем заголовок
        $title = $this->faker->realText(70);
        $article->setTitle($title);

        // Генерируем slug из заголовка
        $slug = $this->slugger->slug($title)->lower();
        $article->setSlug($slug . '-' . $index); // Добавляем индекс для уникальности

        // Краткое описание
        $article->setExcerpt($this->faker->realText(200));

        // Полное содержание (HTML с разметкой)
        $content = $this->generateRichContent();
        $article->setContent($content);

        // Cover image (70% статей имеют изображение)
        if ($this->faker->boolean(70)) {
            $article->setCoverImage($this->faker->imageUrl(1200, 630, 'business', true));
        }

        // Время чтения (1-30 минут)
        $article->setReadingTime($this->faker->numberBetween(1, 30));

        // Количество просмотров
        $article->setViewCount($this->faker->numberBetween(0, 10000));

        // Статус публикации (70% опубликованы)
        $isPublished = $this->faker->boolean(70);
        $article->setIsPublished($isPublished);

        // Featured (20% статей)
        $article->setIsFeatured($this->faker->boolean(20));

        // SEO метаданные
        $article->setMetaTitle($this->faker->realText(60));
        $article->setMetaDescription($this->faker->realText(160));

        // Даты
        $createdAt = $this->faker->dateTimeBetween('-1 year', 'now');
        $article->setCreatedAt(\DateTimeImmutable::createFromMutable($createdAt));

        // PublishedAt (только если статья опубликована)
        if ($isPublished) {
            $publishedAt = $this->faker->dateTimeBetween($createdAt, 'now');
            $article->setPublishedAt(\DateTimeImmutable::createFromMutable($publishedAt));
        }

        return $article;
    }
    
    private function generateRichContent(): string
    {
        $paragraphs = [];
        
        // Генерируем 5-10 абзацев
        $paragraphCount = $this->faker->numberBetween(5, 10);
        
        for ($i = 0; $i < $paragraphCount; $i++) {
            // Каждый 3-й абзац может быть заголовком
            if ($i % 3 === 0 && $i !== 0) {
                $level = $this->faker->randomElement(['h2', 'h3']);
                $paragraphs[] = sprintf(
                    '<%1$s>%2$s</%1$s>',
                    $level,
                    $this->faker->realText(50)
                );
            }
            
            // Основной текст
            $paragraphs[] = sprintf(
                '<p>%s</p>',
                $this->faker->realText(300)
            );
            
            // Добавляем изображение (20% chance)
            if ($this->faker->boolean(20)) {
                $paragraphs[] = sprintf(
                    '<figure><img src="%s" alt="%s"><figcaption>%s</figcaption></figure>',
                    $this->faker->imageUrl(800, 400, 'nature', true),
                    $this->faker->sentence(4),
                    $this->faker->realText(40)
                );
            }
            
            // Добавляем список (10% chance)
            if ($this->faker->boolean(10)) {
                $listItems = [];
                $itemCount = $this->faker->numberBetween(3, 7);
                
                for ($j = 0; $j < $itemCount; $j++) {
                    $listItems[] = sprintf(
                        '<li>%s</li>',
                        $this->faker->realText(60)
                    );
                }
                
                $paragraphs[] = sprintf(
                    '<ul>%s</ul>',
                    implode('', $listItems)
                );
            }
        }
        
        return implode("\n\n", $paragraphs);
    }
}