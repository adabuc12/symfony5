<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220303213035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE complaint (id INT AUTO_INCREMENT NOT NULL, kontrahent_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, date DATETIME NOT NULL, notices VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, number VARCHAR(255) DEFAULT NULL, INDEX IDX_5F2732B538A37D02 (kontrahent_id), INDEX IDX_5F2732B57E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) NOT NULL, delivery_date DATETIME DEFAULT NULL, notices VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_order (delivery_id INT NOT NULL, order_id INT NOT NULL, INDEX IDX_E522750A12136921 (delivery_id), INDEX IDX_E522750A8D9F6D38 (order_id), PRIMARY KEY(delivery_id, order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_driver (delivery_id INT NOT NULL, driver_id INT NOT NULL, INDEX IDX_F77855712136921 (delivery_id), INDEX IDX_F778557C3423909 (driver_id), PRIMARY KEY(delivery_id, driver_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, registration_number VARCHAR(255) NOT NULL, car_wight DOUBLE PRECISION DEFAULT NULL, car_long DOUBLE PRECISION DEFAULT NULL, car_height DOUBLE PRECISION DEFAULT NULL, is_hds TINYINT(1) DEFAULT NULL, axis INT DEFAULT NULL, package_max_weight DOUBLE PRECISION DEFAULT NULL, max_pallets INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver_transport (driver_id INT NOT NULL, transport_id INT NOT NULL, INDEX IDX_49892A37C3423909 (driver_id), INDEX IDX_49892A379909C13F (transport_id), PRIMARY KEY(driver_id, transport_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factory (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, pitch_transport_price DOUBLE PRECISION DEFAULT NULL, price_calculation_mode VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factory_order (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, client_order_id INT DEFAULT NULL, factory_id INT DEFAULT NULL, date_created DATETIME NOT NULL, date_sended DATETIME DEFAULT NULL, number VARCHAR(255) NOT NULL, delivery_date DATE DEFAULT NULL, INDEX IDX_FE8D257AB03A8386 (created_by_id), INDEX IDX_FE8D257AA3795DFD (client_order_id), INDEX IDX_FE8D257AC7AF27D2 (factory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factory_order_transport (factory_order_id INT NOT NULL, transport_id INT NOT NULL, INDEX IDX_FE45830144DD0732 (factory_order_id), INDEX IDX_FE4583019909C13F (transport_id), PRIMARY KEY(factory_order_id, transport_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kontrahent (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, adress VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, nip VARCHAR(255) DEFAULT NULL, notices VARCHAR(255) DEFAULT NULL, post_code VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, class_name VARCHAR(255) NOT NULL, group_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, type_id INT DEFAULT NULL, INDEX IDX_8F3F68C5B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, type VARCHAR(20) NOT NULL, data_created DATETIME NOT NULL, status VARCHAR(20) DEFAULT NULL, INDEX IDX_B6BD307FB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notice (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, is_readed TINYINT(1) DEFAULT NULL, date_created DATETIME NOT NULL, datereaded DATETIME DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, INDEX IDX_480D45C27E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(2550) NOT NULL, description VARCHAR(255) DEFAULT NULL, shortcode VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, kontrahent_id INT DEFAULT NULL, user_id INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, is_ordered TINYINT(1) NOT NULL, delivery_date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, adress VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, kontrahent_group VARCHAR(255) DEFAULT NULL, pickup VARCHAR(255) DEFAULT NULL, is_pickup_wieliczka TINYINT(1) DEFAULT NULL, is_extra_delivery TINYINT(1) DEFAULT NULL, own_pickup TINYINT(1) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, notice VARCHAR(255) DEFAULT NULL, count_pallets TINYINT(1) DEFAULT NULL, order_id INT DEFAULT NULL, INDEX IDX_F529939838A37D02 (kontrahent_id), INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_transport (order_id INT NOT NULL, transport_id INT NOT NULL, INDEX IDX_DA2C5AD68D9F6D38 (order_id), INDEX IDX_DA2C5AD69909C13F (transport_id), PRIMARY KEY(order_id, transport_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_factory_item (id INT AUTO_INCREMENT NOT NULL, factory_order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, pitch_order_id INT DEFAULT NULL, quantity DOUBLE PRECISION NOT NULL, is_confirmed TINYINT(1) NOT NULL, where_pickup VARCHAR(255) DEFAULT NULL, where_add VARCHAR(255) DEFAULT NULL, car_number INT DEFAULT NULL, INDEX IDX_64B272BB44DD0732 (factory_order_id), INDEX IDX_64B272BB4584665A (product_id), INDEX IDX_64B272BBDBAE28F4 (pitch_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, order_ref_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION NOT NULL, pallets DOUBLE PRECISION DEFAULT NULL, INDEX IDX_52EA1F094584665A (product_id), INDEX IDX_52EA1F09E238517C (order_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, kontrahent_id INT DEFAULT NULL, date DATETIME NOT NULL, number VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, amount INT NOT NULL, payment_date DATETIME DEFAULT NULL, is_paid TINYINT(1) DEFAULT NULL, is_printed TINYINT(1) DEFAULT NULL, notices VARCHAR(255) DEFAULT NULL, INDEX IDX_65D29B327E3C61F9 (owner_id), INDEX IDX_65D29B3238A37D02 (kontrahent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pitch_order (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, client_order_id INT DEFAULT NULL, date_created DATETIME NOT NULL, date_sended DATETIME NOT NULL, number VARCHAR(255) NOT NULL, INDEX IDX_3DF62501B03A8386 (created_by_id), INDEX IDX_3DF62501A3795DFD (client_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, manufacture VARCHAR(255) NOT NULL, packaging DOUBLE PRECISION NOT NULL, package_weight DOUBLE PRECISION NOT NULL, unit_weight DOUBLE PRECISION NOT NULL, catalog_price DOUBLE PRECISION NOT NULL, buy_price DOUBLE PRECISION NOT NULL, sell_price_factory_detal DOUBLE PRECISION NOT NULL, sell_price_pitch_detal DOUBLE PRECISION NOT NULL, sell_price_factory_contractors DOUBLE PRECISION NOT NULL, sell_price_pitch_contractors DOUBLE PRECISION NOT NULL, sell_price_factory_wholesale DOUBLE PRECISION NOT NULL, sell_price_pitch_wholesale DOUBLE PRECISION NOT NULL, is_courier TINYINT(1) DEFAULT NULL, courier_cost DOUBLE PRECISION DEFAULT NULL, is_not_available TINYINT(1) DEFAULT NULL, estimated_availability_date DATE DEFAULT NULL, notices VARCHAR(255) DEFAULT NULL, sprzedaz_jednostkowa DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, is_on_promotion TINYINT(1) DEFAULT NULL, is_on_palet TINYINT(1) DEFAULT NULL, is_sell_cost TINYINT(1) DEFAULT NULL, wpid INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_user (product_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7BF4E84584665A (product_id), INDEX IDX_7BF4E8A76ED395 (user_id), PRIMARY KEY(product_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category_product (product_category_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_9A1E202FBE6903FD (product_category_id), INDEX IDX_9A1E202F4584665A (product_id), PRIMARY KEY(product_category_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, price_types LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', cart_condition VARCHAR(255) DEFAULT NULL, product_condition VARCHAR(255) DEFAULT NULL, price_condition VARCHAR(255) DEFAULT NULL, is_enabled TINYINT(1) NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, cart_condition_type VARCHAR(255) DEFAULT NULL, cart_condition_value VARCHAR(255) DEFAULT NULL, product_condition_type VARCHAR(255) DEFAULT NULL, product_condition_value VARCHAR(255) DEFAULT NULL, price_condition_type VARCHAR(255) DEFAULT NULL, price_condition_value VARCHAR(255) DEFAULT NULL, calculation_type VARCHAR(20) DEFAULT NULL, calculation_count_type VARCHAR(10) DEFAULT NULL, calculation_count_value VARCHAR(10) DEFAULT NULL, calculation_count_is_percent TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_product_category (promotion_id INT NOT NULL, product_category_id INT NOT NULL, INDEX IDX_22851FA6139DF194 (promotion_id), INDEX IDX_22851FA6BE6903FD (product_category_id), PRIMARY KEY(promotion_id, product_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, task_owner_id INT NOT NULL, description VARCHAR(255) NOT NULL, date_created DATETIME NOT NULL, date_ended DATETIME DEFAULT NULL, date_to_end DATETIME DEFAULT NULL, priorytet SMALLINT NOT NULL, type VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_527EDB253EFC8BB5 (task_owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_user (task_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FE2042328DB60186 (task_id), INDEX IDX_FE204232A76ED395 (user_id), PRIMARY KEY(task_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transport (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, weight INT NOT NULL, pallet_places INT NOT NULL, price_5 INT NOT NULL, price_10 INT NOT NULL, price_15 INT NOT NULL, price_20 INT NOT NULL, price_25 INT NOT NULL, price_30 INT NOT NULL, price_35 INT NOT NULL, price_40 INT NOT NULL, price_45 INT NOT NULL, price_50 INT NOT NULL, price_55 INT NOT NULL, price_60 INT NOT NULL, price_65 INT NOT NULL, price_70 INT NOT NULL, price_75 INT NOT NULL, price_80 INT NOT NULL, price_85 INT NOT NULL, price_90 INT NOT NULL, price_95 INT NOT NULL, price_100 INT NOT NULL, driver_name VARCHAR(255) NOT NULL, registration_number VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, notices VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, name VARCHAR(255) DEFAULT NULL, surname VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B538A37D02 FOREIGN KEY (kontrahent_id) REFERENCES kontrahent (id)');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B57E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE delivery_order ADD CONSTRAINT FK_E522750A12136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE delivery_order ADD CONSTRAINT FK_E522750A8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE delivery_driver ADD CONSTRAINT FK_F77855712136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE delivery_driver ADD CONSTRAINT FK_F778557C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE driver_transport ADD CONSTRAINT FK_49892A37C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE driver_transport ADD CONSTRAINT FK_49892A379909C13F FOREIGN KEY (transport_id) REFERENCES transport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE factory_order ADD CONSTRAINT FK_FE8D257AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE factory_order ADD CONSTRAINT FK_FE8D257AA3795DFD FOREIGN KEY (client_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE factory_order ADD CONSTRAINT FK_FE8D257AC7AF27D2 FOREIGN KEY (factory_id) REFERENCES factory (id)');
        $this->addSql('ALTER TABLE factory_order_transport ADD CONSTRAINT FK_FE45830144DD0732 FOREIGN KEY (factory_order_id) REFERENCES factory_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE factory_order_transport ADD CONSTRAINT FK_FE4583019909C13F FOREIGN KEY (transport_id) REFERENCES transport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C27E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939838A37D02 FOREIGN KEY (kontrahent_id) REFERENCES kontrahent (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_transport ADD CONSTRAINT FK_DA2C5AD68D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_transport ADD CONSTRAINT FK_DA2C5AD69909C13F FOREIGN KEY (transport_id) REFERENCES transport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_factory_item ADD CONSTRAINT FK_64B272BB44DD0732 FOREIGN KEY (factory_order_id) REFERENCES factory_order (id)');
        $this->addSql('ALTER TABLE order_factory_item ADD CONSTRAINT FK_64B272BB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_factory_item ADD CONSTRAINT FK_64B272BBDBAE28F4 FOREIGN KEY (pitch_order_id) REFERENCES pitch_order (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09E238517C FOREIGN KEY (order_ref_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B327E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B3238A37D02 FOREIGN KEY (kontrahent_id) REFERENCES kontrahent (id)');
        $this->addSql('ALTER TABLE pitch_order ADD CONSTRAINT FK_3DF62501B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pitch_order ADD CONSTRAINT FK_3DF62501A3795DFD FOREIGN KEY (client_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product_user ADD CONSTRAINT FK_7BF4E84584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_user ADD CONSTRAINT FK_7BF4E8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category_product ADD CONSTRAINT FK_9A1E202FBE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category_product ADD CONSTRAINT FK_9A1E202F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_product_category ADD CONSTRAINT FK_22851FA6139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_product_category ADD CONSTRAINT FK_22851FA6BE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB253EFC8BB5 FOREIGN KEY (task_owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_FE2042328DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_FE204232A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_order DROP FOREIGN KEY FK_E522750A12136921');
        $this->addSql('ALTER TABLE delivery_driver DROP FOREIGN KEY FK_F77855712136921');
        $this->addSql('ALTER TABLE delivery_driver DROP FOREIGN KEY FK_F778557C3423909');
        $this->addSql('ALTER TABLE driver_transport DROP FOREIGN KEY FK_49892A37C3423909');
        $this->addSql('ALTER TABLE factory_order DROP FOREIGN KEY FK_FE8D257AC7AF27D2');
        $this->addSql('ALTER TABLE factory_order_transport DROP FOREIGN KEY FK_FE45830144DD0732');
        $this->addSql('ALTER TABLE order_factory_item DROP FOREIGN KEY FK_64B272BB44DD0732');
        $this->addSql('ALTER TABLE complaint DROP FOREIGN KEY FK_5F2732B538A37D02');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939838A37D02');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B3238A37D02');
        $this->addSql('ALTER TABLE delivery_order DROP FOREIGN KEY FK_E522750A8D9F6D38');
        $this->addSql('ALTER TABLE factory_order DROP FOREIGN KEY FK_FE8D257AA3795DFD');
        $this->addSql('ALTER TABLE order_transport DROP FOREIGN KEY FK_DA2C5AD68D9F6D38');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09E238517C');
        $this->addSql('ALTER TABLE pitch_order DROP FOREIGN KEY FK_3DF62501A3795DFD');
        $this->addSql('ALTER TABLE order_factory_item DROP FOREIGN KEY FK_64B272BBDBAE28F4');
        $this->addSql('ALTER TABLE order_factory_item DROP FOREIGN KEY FK_64B272BB4584665A');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE product_user DROP FOREIGN KEY FK_7BF4E84584665A');
        $this->addSql('ALTER TABLE product_category_product DROP FOREIGN KEY FK_9A1E202F4584665A');
        $this->addSql('ALTER TABLE product_category_product DROP FOREIGN KEY FK_9A1E202FBE6903FD');
        $this->addSql('ALTER TABLE promotion_product_category DROP FOREIGN KEY FK_22851FA6BE6903FD');
        $this->addSql('ALTER TABLE promotion_product_category DROP FOREIGN KEY FK_22851FA6139DF194');
        $this->addSql('ALTER TABLE task_user DROP FOREIGN KEY FK_FE2042328DB60186');
        $this->addSql('ALTER TABLE driver_transport DROP FOREIGN KEY FK_49892A379909C13F');
        $this->addSql('ALTER TABLE factory_order_transport DROP FOREIGN KEY FK_FE4583019909C13F');
        $this->addSql('ALTER TABLE order_transport DROP FOREIGN KEY FK_DA2C5AD69909C13F');
        $this->addSql('ALTER TABLE complaint DROP FOREIGN KEY FK_5F2732B57E3C61F9');
        $this->addSql('ALTER TABLE factory_order DROP FOREIGN KEY FK_FE8D257AB03A8386');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5B03A8386');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB03A8386');
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C27E3C61F9');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B327E3C61F9');
        $this->addSql('ALTER TABLE pitch_order DROP FOREIGN KEY FK_3DF62501B03A8386');
        $this->addSql('ALTER TABLE product_user DROP FOREIGN KEY FK_7BF4E8A76ED395');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB253EFC8BB5');
        $this->addSql('ALTER TABLE task_user DROP FOREIGN KEY FK_FE204232A76ED395');
        $this->addSql('DROP TABLE complaint');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE delivery_order');
        $this->addSql('DROP TABLE delivery_driver');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE driver_transport');
        $this->addSql('DROP TABLE factory');
        $this->addSql('DROP TABLE factory_order');
        $this->addSql('DROP TABLE factory_order_transport');
        $this->addSql('DROP TABLE kontrahent');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notice');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_transport');
        $this->addSql('DROP TABLE order_factory_item');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE pitch_order');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_user');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_category_product');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_product_category');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_user');
        $this->addSql('DROP TABLE transport');
        $this->addSql('DROP TABLE user');
    }
}
