<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220206173550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C512136921');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C538A37D02');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5C3423909');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5EDAE188E');
        $this->addSql('DROP INDEX IDX_8F3F68C5EDAE188E ON log');
        $this->addSql('DROP INDEX IDX_8F3F68C5C3423909 ON log');
        $this->addSql('DROP INDEX IDX_8F3F68C512136921 ON log');
        $this->addSql('DROP INDEX IDX_8F3F68C538A37D02 ON log');
        $this->addSql('ALTER TABLE log DROP kontrahent_id, DROP complaint_id, DROP driver_id, DROP delivery_id, DROP context, DROP level, DROP level_name, DROP extra');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE log ADD kontrahent_id INT DEFAULT NULL, ADD complaint_id INT DEFAULT NULL, ADD driver_id INT DEFAULT NULL, ADD delivery_id INT DEFAULT NULL, ADD context LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', ADD level SMALLINT NOT NULL, ADD level_name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD extra LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C512136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C538A37D02 FOREIGN KEY (kontrahent_id) REFERENCES kontrahent (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5EDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id)');
        $this->addSql('CREATE INDEX IDX_8F3F68C5EDAE188E ON log (complaint_id)');
        $this->addSql('CREATE INDEX IDX_8F3F68C5C3423909 ON log (driver_id)');
        $this->addSql('CREATE INDEX IDX_8F3F68C512136921 ON log (delivery_id)');
        $this->addSql('CREATE INDEX IDX_8F3F68C538A37D02 ON log (kontrahent_id)');
    }
}
