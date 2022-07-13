<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706113936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE warehouse_document (id INT AUTO_INCREMENT NOT NULL, kontrahent_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, warehouse_id INT DEFAULT NULL, type VARCHAR(10) NOT NULL, number VARCHAR(20) NOT NULL, date DATETIME NOT NULL, is_brutto TINYINT(1) NOT NULL, INDEX IDX_F821E8D038A37D02 (kontrahent_id), INDEX IDX_F821E8D07E3C61F9 (owner_id), INDEX IDX_F821E8D05080ECDE (warehouse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse_document_order_item (warehouse_document_id INT NOT NULL, order_item_id INT NOT NULL, INDEX IDX_A3CFD35031069942 (warehouse_document_id), INDEX IDX_A3CFD350E415FB15 (order_item_id), PRIMARY KEY(warehouse_document_id, order_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE warehouse_document ADD CONSTRAINT FK_F821E8D038A37D02 FOREIGN KEY (kontrahent_id) REFERENCES kontrahent (id)');
        $this->addSql('ALTER TABLE warehouse_document ADD CONSTRAINT FK_F821E8D07E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE warehouse_document ADD CONSTRAINT FK_F821E8D05080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE warehouse_document_order_item ADD CONSTRAINT FK_A3CFD35031069942 FOREIGN KEY (warehouse_document_id) REFERENCES warehouse_document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE warehouse_document_order_item ADD CONSTRAINT FK_A3CFD350E415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE warehouse_document_order_item DROP FOREIGN KEY FK_A3CFD35031069942');
        $this->addSql('DROP TABLE warehouse_document');
        $this->addSql('DROP TABLE warehouse_document_order_item');
    }
}
