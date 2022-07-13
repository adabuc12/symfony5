<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706084901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD active_warehause_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E1E5F06E FOREIGN KEY (active_warehause_id) REFERENCES warehouse (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E1E5F06E ON user (active_warehause_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E1E5F06E');
        $this->addSql('DROP INDEX IDX_8D93D649E1E5F06E ON user');
        $this->addSql('ALTER TABLE user DROP active_warehause_id');
    }
}
