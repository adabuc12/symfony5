<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220205211045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE log ADD created_by_id INT DEFAULT NULL, DROP text, DROP date');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8F3F68C5B03A8386 ON log (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5B03A8386');
        $this->addSql('DROP INDEX IDX_8F3F68C5B03A8386 ON log');
        $this->addSql('ALTER TABLE log ADD text VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD date DATETIME NOT NULL, DROP created_by_id');
    }
}
