<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211113233842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, order_ref_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, INDEX IDX_52EA1F094584665A (product_id), INDEX IDX_52EA1F09E238517C (order_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, factory_order_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, manufacture VARCHAR(255) NOT NULL, packaging DOUBLE PRECISION NOT NULL, package_weight DOUBLE PRECISION NOT NULL, unit_weight DOUBLE PRECISION NOT NULL, catalog_price DOUBLE PRECISION NOT NULL, buy_price DOUBLE PRECISION NOT NULL, sell_price_factory_detal DOUBLE PRECISION NOT NULL, sell_price_pitch_detal DOUBLE PRECISION NOT NULL, sell_price_factory_contractors DOUBLE PRECISION NOT NULL, sell_price_pitch_contractors DOUBLE PRECISION NOT NULL, sell_price_factory_wholesale DOUBLE PRECISION NOT NULL, sell_price_pitch_wholesale DOUBLE PRECISION NOT NULL, is_courier TINYINT(1) DEFAULT NULL, courier_cost DOUBLE PRECISION DEFAULT NULL, is_not_available TINYINT(1) DEFAULT NULL, estimated_availability_date DATE DEFAULT NULL, notices VARCHAR(255) DEFAULT NULL, INDEX IDX_D34A04AD44DD0732 (factory_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09E238517C FOREIGN KEY (order_ref_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44DD0732 FOREIGN KEY (factory_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE `order` ADD status VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE `order` DROP status, DROP created_at, DROP updated_at');
    }
}
