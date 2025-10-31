<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251031111252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Created initial Category and Article entities with lifecycle callbacks and relationships';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL COMMENT \'Заголовок статьи (отображается пользователям)\', slug VARCHAR(255) NOT NULL COMMENT \'URL-дружественное название статьи (например: \'\'moja-pervaja-statja\'\')\', excerpt LONGTEXT DEFAULT NULL COMMENT \'Краткое описание статьи (отображается в списках статей)\', content LONGTEXT NOT NULL COMMENT \'Полное содержание статьи в формате HTML или Markdown\', cover_image TEXT DEFAULT NULL COMMENT \'URL основного изображения статьи (отображается в шапке статьи и в списках статей)\', reading_time INT NOT NULL COMMENT \'Оценочное время чтения статьи в минутах\', view_count INT NOT NULL COMMENT \'Количество просмотров статьи\', is_published TINYINT(1) NOT NULL COMMENT \'Статус публикации статьи (опубликована или нет)\', is_featured TINYINT(1) NOT NULL COMMENT \'Является ли статья featured/рекомендованной\', meta_title VARCHAR(255) DEFAULT NULL COMMENT \'Мета-заголовок для SEO целей\', meta_description LONGTEXT DEFAULT NULL COMMENT \'Мета-описание для SEO целей\', published_at DATETIME DEFAULT NULL COMMENT \'Дата и время публикации статьи(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'Дата и время создания статьи(DC2Type:datetime_immutable)\', update_at DATETIME DEFAULT NULL COMMENT \'Дата и время последнего обновления статьи(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_23A0E662B36786B (title), UNIQUE INDEX UNIQ_23A0E66989D9B62 (slug), INDEX IDX_23A0E6612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL COMMENT \'Название категории (Веб-разработка, Мобильные приложения, UI/UX)\', slug VARCHAR(100) NOT NULL COMMENT \'URL-дружественное название категории (web-development, mobile-apps)\', description LONGTEXT DEFAULT NULL COMMENT \'Подробное описание категории и что в нее входит\', color VARCHAR(7) DEFAULT NULL COMMENT \'Цвет категории в формате HEX (например: #FF5733)\', icon VARCHAR(50) DEFAULT NULL COMMENT \'Название иконки из PrimeIcons (pi-desktop, pi-mobile, pi-palette)\', image VARCHAR(500) DEFAULT NULL COMMENT \'URL изображения категории (отображается в шапке категории)\', meta_title VARCHAR(255) DEFAULT NULL COMMENT \'SEO title для страницы категории\', meta_description LONGTEXT DEFAULT NULL COMMENT \'SEO description для страницы категории\', sort_order INT NOT NULL COMMENT \'Порядок сортировки категорий (чем выше число, тем выше в списке)\', is_visible TINYINT(1) NOT NULL COMMENT \'Видимость категории на сайте\', item_count INT NOT NULL COMMENT \'Количество элементов (статей) в категории\', created_at DATETIME NOT NULL COMMENT \'Дата и время создания категории(DC2Type:datetime_immutable)\', update_at DATETIME DEFAULT NULL COMMENT \'Дата и время последнего обновления категории(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_64C19C15E237E06 (name), UNIQUE INDEX UNIQ_64C19C1989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE category');
    }
}
