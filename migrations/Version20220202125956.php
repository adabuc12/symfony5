<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220202125956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion ADD calculation_type VARCHAR(20) DEFAULT NULL, ADD calculation_count_type VARCHAR(10) DEFAULT NULL, ADD calculation_count_value VARCHAR(10) DEFAULT NULL, ADD calculation_count_is_percent TINYINT(1) DEFAULT NULL, CHANGE cart_condition cart_condition VARCHAR(255) DEFAULT NULL, CHANGE price_condition price_condition VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion DROP calculation_type, DROP calculation_count_type, DROP calculation_count_value, DROP calculation_count_is_percent, CHANGE cart_condition cart_condition VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE price_condition price_condition VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
