<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220105085003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factory_order ADD factory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE factory_order ADD CONSTRAINT FK_FE8D257AC7AF27D2 FOREIGN KEY (factory_id) REFERENCES factory (id)');
        $this->addSql('CREATE INDEX IDX_FE8D257AC7AF27D2 ON factory_order (factory_id)');
        $this->addSql('ALTER TABLE log ADD message LONGTEXT NOT NULL, ADD context LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD level SMALLINT NOT NULL, ADD level_name VARCHAR(50) NOT NULL, ADD extra LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factory_order DROP FOREIGN KEY FK_FE8D257AC7AF27D2');
        $this->addSql('DROP INDEX IDX_FE8D257AC7AF27D2 ON factory_order');
        $this->addSql('ALTER TABLE factory_order DROP factory_id');
        $this->addSql('ALTER TABLE log DROP message, DROP context, DROP level, DROP level_name, DROP extra, DROP created_at');
    }
}
