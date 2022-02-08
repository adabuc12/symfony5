<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131234819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, price_types LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', cart_condition VARCHAR(255) NOT NULL, product_condition VARCHAR(255) DEFAULT NULL, price_condition VARCHAR(255) NOT NULL, is_enabled TINYINT(1) NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_product_category (promotion_id INT NOT NULL, product_category_id INT NOT NULL, INDEX IDX_22851FA6139DF194 (promotion_id), INDEX IDX_22851FA6BE6903FD (product_category_id), PRIMARY KEY(promotion_id, product_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotion_product_category ADD CONSTRAINT FK_22851FA6139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_product_category ADD CONSTRAINT FK_22851FA6BE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion_product_category DROP FOREIGN KEY FK_22851FA6139DF194');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_product_category');
    }
}
