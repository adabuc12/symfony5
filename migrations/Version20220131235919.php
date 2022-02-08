<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131235919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion ADD name VARCHAR(255) NOT NULL, ADD cart_condition_type VARCHAR(255) DEFAULT NULL, ADD cart_condition_value VARCHAR(255) DEFAULT NULL, ADD product_condition_type VARCHAR(255) DEFAULT NULL, ADD product_condition_value VARCHAR(255) DEFAULT NULL, ADD price_condition_type VARCHAR(255) DEFAULT NULL, ADD price_condition_value VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion DROP name, DROP cart_condition_type, DROP cart_condition_value, DROP product_condition_type, DROP product_condition_value, DROP price_condition_type, DROP price_condition_value');
    }
}
