<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261029081406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename update_at to updated_at in article and category tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article RENAME COLUMN update_at TO updated_at');
        $this->addSql('ALTER TABLE category RENAME COLUMN update_at TO updated_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article RENAME COLUMN updated_at TO update_at');
        $this->addSql('ALTER TABLE category RENAME COLUMN updated_at TO update_at');
    }
}
