<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211218120828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE complaint (id INT AUTO_INCREMENT NOT NULL, kontrahent_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, date DATETIME NOT NULL, notices VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, number VARCHAR(255) DEFAULT NULL, INDEX IDX_5F2732B538A37D02 (kontrahent_id), INDEX IDX_5F2732B57E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B538A37D02 FOREIGN KEY (kontrahent_id) REFERENCES kontrahent (id)');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B57E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE log ADD complaint_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5EDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id)');
        $this->addSql('CREATE INDEX IDX_8F3F68C5EDAE188E ON log (complaint_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5EDAE188E');
        $this->addSql('DROP TABLE complaint');
        $this->addSql('DROP INDEX IDX_8F3F68C5EDAE188E ON log');
        $this->addSql('ALTER TABLE log DROP complaint_id');
    }
}
