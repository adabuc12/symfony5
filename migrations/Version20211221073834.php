<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211221073834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD kontrahent_group VARCHAR(255) NOT NULL, ADD pickup VARCHAR(255) NOT NULL, ADD is_pickup_wieliczka TINYINT(1) DEFAULT NULL, ADD is_extra_delivery TINYINT(1) DEFAULT NULL, ADD own_pickup TINYINT(1) DEFAULT NULL, DROP date, DROP allowed_car_size');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD date DATETIME NOT NULL, ADD allowed_car_size VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP kontrahent_group, DROP pickup, DROP is_pickup_wieliczka, DROP is_extra_delivery, DROP own_pickup');
    }
}
