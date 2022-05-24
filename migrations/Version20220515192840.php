<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220515192840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery ADD pickup VARCHAR(255) DEFAULT NULL, ADD delivery_adress VARCHAR(255) DEFAULT NULL, ADD second_pickup VARCHAR(255) DEFAULT NULL, ADD second_delivery_adress VARCHAR(255) DEFAULT NULL, ADD transshipment VARCHAR(255) DEFAULT NULL, ADD is_transshipment TINYINT(1) DEFAULT NULL, ADD is_courier TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery DROP pickup, DROP delivery_adress, DROP second_pickup, DROP second_delivery_adress, DROP transshipment, DROP is_transshipment, DROP is_courier');
    }
}
