<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211207152401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD kontrahent_id INT DEFAULT NULL, ADD adress VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD allowed_car_size VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939838A37D02 FOREIGN KEY (kontrahent_id) REFERENCES kontrahent (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F529939838A37D02 ON `order` (kontrahent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939838A37D02');
        $this->addSql('DROP INDEX UNIQ_F529939838A37D02 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP kontrahent_id, DROP adress, DROP phone, DROP allowed_car_size');
    }
}
