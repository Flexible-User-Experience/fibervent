<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20191011100614 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE delivery_note_time_register (id INT AUTO_INCREMENT NOT NULL, delivery_note_id INT DEFAULT NULL, type INT NOT NULL, shift INT NOT NULL, begin TIME NOT NULL, end TIME NOT NULL, total_hours DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_4F5D9AA2CF3B78B (delivery_note_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_note (id INT AUTO_INCREMENT NOT NULL, work_order_id INT DEFAULT NULL, admin_user_id INT NOT NULL, vehicle_id INT DEFAULT NULL, date DATETIME NOT NULL, repair_windmill_sections JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', crane_company VARCHAR(100) DEFAULT NULL, crane_driver VARCHAR(100) DEFAULT NULL, repair_access_types JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', observations VARCHAR(100) DEFAULT NULL, total_trip_hours DOUBLE PRECISION DEFAULT NULL, total_work_hours DOUBLE PRECISION DEFAULT NULL, total_stop_hours DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_1E21328E582AE764 (work_order_id), INDEX IDX_1E21328E6352511C (admin_user_id), INDEX IDX_1E21328E545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_order_task (id INT AUTO_INCREMENT NOT NULL, work_order_id INT DEFAULT NULL, blade_damage_id INT DEFAULT NULL, windmill_blade_id INT DEFAULT NULL, is_from_audit TINYINT(1) NOT NULL, position INT DEFAULT NULL, radius INT DEFAULT NULL, distance INT DEFAULT NULL, size INT DEFAULT NULL, edge INT DEFAULT NULL, is_completed TINYINT(1) DEFAULT \'0\' NOT NULL, description VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, removed_at DATETIME DEFAULT NULL, windmill_id INT DEFAULT NULL, INDEX IDX_4EC4CE1582AE764 (work_order_id), UNIQUE INDEX UNIQ_4EC4CE180036B9B (blade_damage_id), INDEX IDX_4EC4CE1772F8851 (windmill_blade_id), INDEX IDX_4EC4CE120C49033 (windmill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE worker_timesheet (id INT AUTO_INCREMENT NOT NULL, delivery_note_id INT NOT NULL, admin_user_id INT NOT NULL, work_description VARCHAR(100) DEFAULT NULL, total_normal_hours DOUBLE PRECISION DEFAULT NULL, total_vertical_hours DOUBLE PRECISION DEFAULT NULL, total_inclement_weather_hours DOUBLE PRECISION DEFAULT NULL, total_trip_hours DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_F5A445C72CF3B78B (delivery_note_id), INDEX IDX_F5A445C76352511C (admin_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE presence_monitoring (id INT AUTO_INCREMENT NOT NULL, admin_user_id INT NOT NULL, date DATETIME NOT NULL, morning_hour_begin TIME DEFAULT NULL, morning_hour_end TIME DEFAULT NULL, afternoon_hour_begin TIME DEFAULT NULL, afternoon_hour_end TIME DEFAULT NULL, total_hours DOUBLE PRECISION DEFAULT NULL, normal_hours DOUBLE PRECISION DEFAULT NULL, extra_hours DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_982B717B6352511C (admin_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_order (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, windfarm_id INT DEFAULT NULL, audit_id INT DEFAULT NULL, project_number VARCHAR(45) NOT NULL, is_from_audit TINYINT(1) NOT NULL, certifying_company_name VARCHAR(100) DEFAULT NULL, certifying_company_contact_person VARCHAR(100) DEFAULT NULL, certifying_company_phone VARCHAR(100) DEFAULT NULL, certifying_company_email VARCHAR(100) DEFAULT NULL, repair_access_types JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, removed_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_DDD2E8B78134F41E (project_number), INDEX IDX_DDD2E8B79395C3F3 (customer_id), INDEX IDX_DDD2E8B7A9FC0822 (windfarm_id), INDEX IDX_DDD2E8B7BD29F359 (audit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE non_standard_used_material (id INT AUTO_INCREMENT NOT NULL, delivery_note_id INT DEFAULT NULL, quantity DOUBLE PRECISION NOT NULL, item INT NOT NULL, description VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_B2FA0EFA2CF3B78B (delivery_note_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, license_plate VARCHAR(20) NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, removed_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1B80E486F5AA79D0 (license_plate), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE delivery_note_time_register ADD CONSTRAINT FK_4F5D9AA2CF3B78B FOREIGN KEY (delivery_note_id) REFERENCES delivery_note (id)');
        $this->addSql('ALTER TABLE delivery_note ADD CONSTRAINT FK_1E21328E582AE764 FOREIGN KEY (work_order_id) REFERENCES work_order (id)');
        $this->addSql('ALTER TABLE delivery_note ADD CONSTRAINT FK_1E21328E6352511C FOREIGN KEY (admin_user_id) REFERENCES admin_user (id)');
        $this->addSql('ALTER TABLE delivery_note ADD CONSTRAINT FK_1E21328E545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE work_order_task ADD CONSTRAINT FK_4EC4CE1582AE764 FOREIGN KEY (work_order_id) REFERENCES work_order (id)');
        $this->addSql('ALTER TABLE work_order_task ADD CONSTRAINT FK_4EC4CE180036B9B FOREIGN KEY (blade_damage_id) REFERENCES blade_damage (id)');
        $this->addSql('ALTER TABLE work_order_task ADD CONSTRAINT FK_4EC4CE1772F8851 FOREIGN KEY (windmill_blade_id) REFERENCES windmill_blade (id)');
        $this->addSql('ALTER TABLE worker_timesheet ADD CONSTRAINT FK_F5A445C72CF3B78B FOREIGN KEY (delivery_note_id) REFERENCES delivery_note (id)');
        $this->addSql('ALTER TABLE worker_timesheet ADD CONSTRAINT FK_F5A445C76352511C FOREIGN KEY (admin_user_id) REFERENCES admin_user (id)');
        $this->addSql('ALTER TABLE presence_monitoring ADD CONSTRAINT FK_982B717B6352511C FOREIGN KEY (admin_user_id) REFERENCES admin_user (id)');
        $this->addSql('ALTER TABLE work_order ADD CONSTRAINT FK_DDD2E8B79395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE work_order ADD CONSTRAINT FK_DDD2E8B7A9FC0822 FOREIGN KEY (windfarm_id) REFERENCES windfarm (id)');
        $this->addSql('ALTER TABLE work_order ADD CONSTRAINT FK_DDD2E8B7BD29F359 FOREIGN KEY (audit_id) REFERENCES audit (id)');
        $this->addSql('ALTER TABLE non_standard_used_material ADD CONSTRAINT FK_B2FA0EFA2CF3B78B FOREIGN KEY (delivery_note_id) REFERENCES delivery_note (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE delivery_note_time_register DROP FOREIGN KEY FK_4F5D9AA2CF3B78B');
        $this->addSql('ALTER TABLE worker_timesheet DROP FOREIGN KEY FK_F5A445C72CF3B78B');
        $this->addSql('ALTER TABLE non_standard_used_material DROP FOREIGN KEY FK_B2FA0EFA2CF3B78B');
        $this->addSql('ALTER TABLE delivery_note DROP FOREIGN KEY FK_1E21328E582AE764');
        $this->addSql('ALTER TABLE work_order_task DROP FOREIGN KEY FK_4EC4CE1582AE764');
        $this->addSql('ALTER TABLE delivery_note DROP FOREIGN KEY FK_1E21328E545317D1');
        $this->addSql('DROP TABLE delivery_note_time_register');
        $this->addSql('DROP TABLE delivery_note');
        $this->addSql('DROP TABLE work_order_task');
        $this->addSql('DROP TABLE worker_timesheet');
        $this->addSql('DROP TABLE presence_monitoring');
        $this->addSql('DROP TABLE work_order');
        $this->addSql('DROP TABLE non_standard_used_material');
        $this->addSql('DROP TABLE vehicle');
    }
}
