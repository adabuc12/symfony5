<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211217214057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE order_kontrahent');
        $this->addSql('ALTER TABLE `order` ADD kontrahent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939838A37D02 FOREIGN KEY (kontrahent_id) REFERENCES kontrahent (id)');
        $this->addSql('CREATE INDEX IDX_F529939838A37D02 ON `order` (kontrahent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_kontrahent (order_id INT NOT NULL, kontrahent_id INT NOT NULL, INDEX IDX_D9EBB57C38A37D02 (kontrahent_id), INDEX IDX_D9EBB57C8D9F6D38 (order_id), PRIMARY KEY(order_id, kontrahent_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_kontrahent ADD CONSTRAINT FK_D9EBB57C38A37D02 FOREIGN KEY (kontrahent_id) REFERENCES kontrahent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_kontrahent ADD CONSTRAINT FK_D9EBB57C8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939838A37D02');
        $this->addSql('DROP INDEX IDX_F529939838A37D02 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP kontrahent_id');
    }
}
