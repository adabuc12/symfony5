<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220220121209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE factory_order_transport (factory_order_id INT NOT NULL, transport_id INT NOT NULL, INDEX IDX_FE45830144DD0732 (factory_order_id), INDEX IDX_FE4583019909C13F (transport_id), PRIMARY KEY(factory_order_id, transport_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE factory_order_transport ADD CONSTRAINT FK_FE45830144DD0732 FOREIGN KEY (factory_order_id) REFERENCES factory_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE factory_order_transport ADD CONSTRAINT FK_FE4583019909C13F FOREIGN KEY (transport_id) REFERENCES transport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE factory_order CHANGE date_sended date_sended DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE factory_order_transport');
        $this->addSql('ALTER TABLE factory_order CHANGE date_sended date_sended DATETIME NOT NULL');
    }
}
