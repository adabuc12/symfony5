<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220501185614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1AD5CDBF FOREIGN KEY (cart_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F1AD5CDBF ON message (cart_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1AD5CDBF');
        $this->addSql('DROP INDEX IDX_B6BD307F1AD5CDBF ON message');
        $this->addSql('ALTER TABLE message DROP cart_id');
    }
}
