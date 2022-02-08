<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211228225351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE factory_order (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, date_created DATETIME NOT NULL, date_sended DATETIME NOT NULL, number VARCHAR(255) NOT NULL, INDEX IDX_FE8D257AB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_factory_item (id INT AUTO_INCREMENT NOT NULL, factory_order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity DOUBLE PRECISION NOT NULL, INDEX IDX_64B272BB44DD0732 (factory_order_id), INDEX IDX_64B272BB4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE factory_order ADD CONSTRAINT FK_FE8D257AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_factory_item ADD CONSTRAINT FK_64B272BB44DD0732 FOREIGN KEY (factory_order_id) REFERENCES factory_order (id)');
        $this->addSql('ALTER TABLE order_factory_item ADD CONSTRAINT FK_64B272BB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_factory_item DROP FOREIGN KEY FK_64B272BB44DD0732');
        $this->addSql('DROP TABLE factory_order');
        $this->addSql('DROP TABLE order_factory_item');
    }
}
