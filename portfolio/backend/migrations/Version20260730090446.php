<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260730090446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id SERIAL NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, excerpt TEXT DEFAULT NULL, content TEXT NOT NULL, cover_image TEXT DEFAULT NULL, reading_time INT NOT NULL, view_count INT NOT NULL, is_published BOOLEAN NOT NULL, is_featured BOOLEAN NOT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description TEXT DEFAULT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E662B36786B ON article (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66989D9B62 ON article (slug)');
        $this->addSql('CREATE INDEX IDX_23A0E6612469DE2 ON article (category_id)');
        $this->addSql('COMMENT ON COLUMN article.title IS \'Заголовок статьи (отображается пользователям)\'');
        $this->addSql('COMMENT ON COLUMN article.slug IS \'URL-дружественное название статьи (например: \'\'moja-pervaja-statja\'\')\'');
        $this->addSql('COMMENT ON COLUMN article.excerpt IS \'Краткое описание статьи (отображается в списках статей)\'');
        $this->addSql('COMMENT ON COLUMN article.content IS \'Полное содержание статьи в формате HTML или Markdown\'');
        $this->addSql('COMMENT ON COLUMN article.cover_image IS \'URL основного изображения статьи (отображается в шапке статьи и в списках статей)\'');
        $this->addSql('COMMENT ON COLUMN article.reading_time IS \'Оценочное время чтения статьи в минутах\'');
        $this->addSql('COMMENT ON COLUMN article.view_count IS \'Количество просмотров статьи\'');
        $this->addSql('COMMENT ON COLUMN article.is_published IS \'Статус публикации статьи (опубликована или нет)\'');
        $this->addSql('COMMENT ON COLUMN article.is_featured IS \'Является ли статья featured/рекомендованной\'');
        $this->addSql('COMMENT ON COLUMN article.meta_title IS \'Мета-заголовок для SEO целей\'');
        $this->addSql('COMMENT ON COLUMN article.meta_description IS \'Мета-описание для SEO целей\'');
        $this->addSql('COMMENT ON COLUMN article.published_at IS \'Дата и время публикации статьи(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN article.created_at IS \'Дата и время создания статьи(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN article.update_at IS \'Дата и время последнего обновления статьи(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE category (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, color VARCHAR(7) DEFAULT NULL, icon VARCHAR(50) DEFAULT NULL, image VARCHAR(500) DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description TEXT DEFAULT NULL, sort_order INT NOT NULL, is_visible BOOLEAN NOT NULL, item_count INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C15E237E06 ON category (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C1989D9B62 ON category (slug)');
        $this->addSql('COMMENT ON COLUMN category.name IS \'Название категории (Веб-разработка, Мобильные приложения, UI/UX)\'');
        $this->addSql('COMMENT ON COLUMN category.slug IS \'URL-дружественное название категории (web-development, mobile-apps)\'');
        $this->addSql('COMMENT ON COLUMN category.description IS \'Подробное описание категории и что в нее входит\'');
        $this->addSql('COMMENT ON COLUMN category.color IS \'Цвет категории в формате HEX (например: #FF5733)\'');
        $this->addSql('COMMENT ON COLUMN category.icon IS \'Название иконки из PrimeIcons (pi-desktop, pi-mobile, pi-palette)\'');
        $this->addSql('COMMENT ON COLUMN category.image IS \'URL изображения категории (отображается в шапке категории)\'');
        $this->addSql('COMMENT ON COLUMN category.meta_title IS \'SEO title для страницы категории\'');
        $this->addSql('COMMENT ON COLUMN category.meta_description IS \'SEO description для страницы категории\'');
        $this->addSql('COMMENT ON COLUMN category.sort_order IS \'Порядок сортировки категорий (чем выше число, тем выше в списке)\'');
        $this->addSql('COMMENT ON COLUMN category.is_visible IS \'Видимость категории на сайте\'');
        $this->addSql('COMMENT ON COLUMN category.item_count IS \'Количество элементов (статей) в категории\'');
        $this->addSql('COMMENT ON COLUMN category.created_at IS \'Дата и время создания категории(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN category.update_at IS \'Дата и время последнего обновления категории(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE project (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description TEXT NOT NULL, content TEXT DEFAULT NULL, cover_image VARCHAR(500) DEFAULT NULL, project_url VARCHAR(500) DEFAULT NULL, github_url VARCHAR(500) DEFAULT NULL, demo_url VARCHAR(500) DEFAULT NULL, status VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE2B36786B ON project (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE989D9B62 ON project (slug)');
        $this->addSql('COMMENT ON COLUMN project.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN project.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE project_tag (project_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(project_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_91F26D60166D1F9C ON project_tag (project_id)');
        $this->addSql('CREATE INDEX IDX_91F26D60BAD26311 ON project_tag (tag_id)');
        $this->addSql('CREATE TABLE tag (id SERIAL NOT NULL, name VARCHAR(50) NOT NULL, description TEXT DEFAULT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B7835E237E06 ON tag (name)');
        $this->addSql('COMMENT ON COLUMN tag.name IS \'Название тега (например: PHP, JavaScript, DevOps и т.д.)\'');
        $this->addSql('COMMENT ON COLUMN tag.description IS \'Описание тега (необязательно)\'');
        $this->addSql('COMMENT ON COLUMN tag.create_at IS \'Дата и время создания тега(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tag_article (tag_id INT NOT NULL, article_id INT NOT NULL, PRIMARY KEY(tag_id, article_id))');
        $this->addSql('CREATE INDEX IDX_300B23CCBAD26311 ON tag_article (tag_id)');
        $this->addSql('CREATE INDEX IDX_300B23CC7294869C ON tag_article (article_id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_91F26D60166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_91F26D60BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag_article ADD CONSTRAINT FK_300B23CCBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag_article ADD CONSTRAINT FK_300B23CC7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE article DROP CONSTRAINT FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE project_tag DROP CONSTRAINT FK_91F26D60166D1F9C');
        $this->addSql('ALTER TABLE project_tag DROP CONSTRAINT FK_91F26D60BAD26311');
        $this->addSql('ALTER TABLE tag_article DROP CONSTRAINT FK_300B23CCBAD26311');
        $this->addSql('ALTER TABLE tag_article DROP CONSTRAINT FK_300B23CC7294869C');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_article');
    }
}
