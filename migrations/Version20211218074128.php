<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211218074128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, kontrahent_id INT DEFAULT NULL, date DATETIME NOT NULL, number INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, amount INT NOT NULL, payment_date DATETIME DEFAULT NULL, is_paid TINYINT(1) DEFAULT NULL, is_printed TINYINT(1) DEFAULT NULL, notices VARCHAR(255) DEFAULT NULL, INDEX IDX_65D29B327E3C61F9 (owner_id), INDEX IDX_65D29B3238A37D02 (kontrahent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B327E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B3238A37D02 FOREIGN KEY (kontrahent_id) REFERENCES kontrahent (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE payments');
    }
}
