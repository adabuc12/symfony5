<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211229151329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factory_order ADD client_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE factory_order ADD CONSTRAINT FK_FE8D257AA3795DFD FOREIGN KEY (client_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_FE8D257AA3795DFD ON factory_order (client_order_id)');
        $this->addSql('ALTER TABLE pitch_order ADD client_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pitch_order ADD CONSTRAINT FK_3DF62501A3795DFD FOREIGN KEY (client_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_3DF62501A3795DFD ON pitch_order (client_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factory_order DROP FOREIGN KEY FK_FE8D257AA3795DFD');
        $this->addSql('DROP INDEX IDX_FE8D257AA3795DFD ON factory_order');
        $this->addSql('ALTER TABLE factory_order DROP client_order_id');
        $this->addSql('ALTER TABLE pitch_order DROP FOREIGN KEY FK_3DF62501A3795DFD');
        $this->addSql('DROP INDEX IDX_3DF62501A3795DFD ON pitch_order');
        $this->addSql('ALTER TABLE pitch_order DROP client_order_id');
    }
}
