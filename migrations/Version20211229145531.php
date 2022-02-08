<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211229145531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pitch_order (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, date_created DATETIME NOT NULL, date_sended DATETIME NOT NULL, number VARCHAR(255) NOT NULL, INDEX IDX_3DF62501B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pitch_order ADD CONSTRAINT FK_3DF62501B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_factory_item ADD pitch_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_factory_item ADD CONSTRAINT FK_64B272BBDBAE28F4 FOREIGN KEY (pitch_order_id) REFERENCES pitch_order (id)');
        $this->addSql('CREATE INDEX IDX_64B272BBDBAE28F4 ON order_factory_item (pitch_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_factory_item DROP FOREIGN KEY FK_64B272BBDBAE28F4');
        $this->addSql('DROP TABLE pitch_order');
        $this->addSql('DROP INDEX IDX_64B272BBDBAE28F4 ON order_factory_item');
        $this->addSql('ALTER TABLE order_factory_item DROP pitch_order_id');
    }
}
