<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221028102403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создал сущность Technology Tag. Создал отношение с сущностью Article ManyToMany';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE technology_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology_tag_article (technology_tag_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_11626E2CF0EDB5BA (technology_tag_id), INDEX IDX_11626E2C7294869C (article_id), PRIMARY KEY(technology_tag_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE technology_tag_article ADD CONSTRAINT FK_11626E2CF0EDB5BA FOREIGN KEY (technology_tag_id) REFERENCES technology_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE technology_tag_article ADD CONSTRAINT FK_11626E2C7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE technology_tag_article DROP FOREIGN KEY FK_11626E2CF0EDB5BA');
        $this->addSql('DROP TABLE technology_tag');
        $this->addSql('DROP TABLE technology_tag_article');
    }
}
