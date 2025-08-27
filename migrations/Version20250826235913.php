<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250826235913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ocpp_charge_purchase (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ocpp_terminal_id INT UNSIGNED NOT NULL, created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, created_at DATETIME(6) DEFAULT NULL, updated_at DATETIME(6) DEFAULT NULL, deleted TINYINT(1) NOT NULL, uuid VARCHAR(45) NOT NULL, banking_transaction_uuid VARCHAR(255) DEFAULT NULL, idp_account_uuid VARCHAR(255) DEFAULT NULL, INDEX IDX_6FD79C0E9ADB59CA (ocpp_terminal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ocpp_terminal_stations (id INT UNSIGNED AUTO_INCREMENT NOT NULL, created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, created_at DATETIME(6) DEFAULT NULL, updated_at DATETIME(6) DEFAULT NULL, deleted TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ocpp_terminals (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ocpp_station_id INT UNSIGNED NOT NULL, created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, created_at DATETIME(6) DEFAULT NULL, updated_at DATETIME(6) DEFAULT NULL, deleted TINYINT(1) NOT NULL, uuid VARCHAR(45) NOT NULL, name VARCHAR(255) NOT NULL, protocol_version VARCHAR(10) NOT NULL, INDEX IDX_D6EF2BFC861621BA (ocpp_station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ocpp_vehicles (id INT UNSIGNED AUTO_INCREMENT NOT NULL, created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, created_at DATETIME(6) DEFAULT NULL, updated_at DATETIME(6) DEFAULT NULL, deleted TINYINT(1) NOT NULL, plate_number VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ocpp_charge_purchase ADD CONSTRAINT FK_6FD79C0E9ADB59CA FOREIGN KEY (ocpp_terminal_id) REFERENCES ocpp_terminals (id)');
        $this->addSql('ALTER TABLE ocpp_terminals ADD CONSTRAINT FK_D6EF2BFC861621BA FOREIGN KEY (ocpp_station_id) REFERENCES ocpp_terminal_stations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ocpp_charge_purchase DROP FOREIGN KEY FK_6FD79C0E9ADB59CA');
        $this->addSql('ALTER TABLE ocpp_terminals DROP FOREIGN KEY FK_D6EF2BFC861621BA');
        $this->addSql('DROP TABLE ocpp_charge_purchase');
        $this->addSql('DROP TABLE ocpp_terminal_stations');
        $this->addSql('DROP TABLE ocpp_terminals');
        $this->addSql('DROP TABLE ocpp_vehicles');
    }
}
