<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160712174928 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blade_photo (id INT AUTO_INCREMENT NOT NULL, audit_windmill_blade_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, removed_at DATETIME DEFAULT NULL, gps_longitude DOUBLE PRECISION DEFAULT NULL, gps_latitude DOUBLE PRECISION DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, INDEX IDX_419EEB28A50CFBEE (audit_windmill_blade_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE observation (id INT AUTO_INCREMENT NOT NULL, audit_windmill_blade_id INT DEFAULT NULL, position INT NOT NULL, damage_number INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, removed_at DATETIME DEFAULT NULL, observations TEXT DEFAULT NULL, INDEX IDX_C576DBE0A50CFBEE (audit_windmill_blade_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blade_photo ADD CONSTRAINT FK_419EEB28A50CFBEE FOREIGN KEY (audit_windmill_blade_id) REFERENCES audit_windmill_blade (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0A50CFBEE FOREIGN KEY (audit_windmill_blade_id) REFERENCES audit_windmill_blade (id)');
        $this->addSql('ALTER TABLE audit_windmill_blade DROP observations');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE blade_photo');
        $this->addSql('DROP TABLE observation');
        $this->addSql('ALTER TABLE audit_windmill_blade ADD observations TEXT DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
