<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211218125000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) NOT NULL, delivery_date DATETIME DEFAULT NULL, notices VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_order (delivery_id INT NOT NULL, order_id INT NOT NULL, INDEX IDX_E522750A12136921 (delivery_id), INDEX IDX_E522750A8D9F6D38 (order_id), PRIMARY KEY(delivery_id, order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_driver (delivery_id INT NOT NULL, driver_id INT NOT NULL, INDEX IDX_F77855712136921 (delivery_id), INDEX IDX_F778557C3423909 (driver_id), PRIMARY KEY(delivery_id, driver_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, registration_number VARCHAR(255) NOT NULL, car_wight DOUBLE PRECISION DEFAULT NULL, car_long DOUBLE PRECISION DEFAULT NULL, car_height DOUBLE PRECISION DEFAULT NULL, is_hds TINYINT(1) DEFAULT NULL, axis INT DEFAULT NULL, package_max_weight DOUBLE PRECISION DEFAULT NULL, max_pallets INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver_transport (driver_id INT NOT NULL, transport_id INT NOT NULL, INDEX IDX_49892A37C3423909 (driver_id), INDEX IDX_49892A379909C13F (transport_id), PRIMARY KEY(driver_id, transport_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE delivery_order ADD CONSTRAINT FK_E522750A12136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE delivery_order ADD CONSTRAINT FK_E522750A8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE delivery_driver ADD CONSTRAINT FK_F77855712136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE delivery_driver ADD CONSTRAINT FK_F778557C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE driver_transport ADD CONSTRAINT FK_49892A37C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE driver_transport ADD CONSTRAINT FK_49892A379909C13F FOREIGN KEY (transport_id) REFERENCES transport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE log ADD driver_id INT DEFAULT NULL, ADD delivery_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C512136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('CREATE INDEX IDX_8F3F68C5C3423909 ON log (driver_id)');
        $this->addSql('CREATE INDEX IDX_8F3F68C512136921 ON log (delivery_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_order DROP FOREIGN KEY FK_E522750A12136921');
        $this->addSql('ALTER TABLE delivery_driver DROP FOREIGN KEY FK_F77855712136921');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C512136921');
        $this->addSql('ALTER TABLE delivery_driver DROP FOREIGN KEY FK_F778557C3423909');
        $this->addSql('ALTER TABLE driver_transport DROP FOREIGN KEY FK_49892A37C3423909');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5C3423909');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE delivery_order');
        $this->addSql('DROP TABLE delivery_driver');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE driver_transport');
        $this->addSql('DROP INDEX IDX_8F3F68C5C3423909 ON log');
        $this->addSql('DROP INDEX IDX_8F3F68C512136921 ON log');
        $this->addSql('ALTER TABLE log DROP driver_id, DROP delivery_id');
    }
}
