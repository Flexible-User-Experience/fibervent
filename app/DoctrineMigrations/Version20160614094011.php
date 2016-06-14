<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160614094011 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin_user (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, date_of_birth DATETIME DEFAULT NULL, firstname VARCHAR(64) DEFAULT NULL, lastname VARCHAR(64) DEFAULT NULL, website VARCHAR(64) DEFAULT NULL, biography VARCHAR(1000) DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, locale VARCHAR(8) DEFAULT NULL, timezone VARCHAR(64) DEFAULT NULL, phone VARCHAR(64) DEFAULT NULL, facebook_uid VARCHAR(255) DEFAULT NULL, facebook_name VARCHAR(255) DEFAULT NULL, facebook_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', twitter_uid VARCHAR(255) DEFAULT NULL, twitter_name VARCHAR(255) DEFAULT NULL, twitter_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', gplus_uid VARCHAR(255) DEFAULT NULL, gplus_name VARCHAR(255) DEFAULT NULL, gplus_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', token VARCHAR(255) DEFAULT NULL, two_step_code VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_AD8A54A992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_AD8A54A9A0D96FBF (email_canonical), INDEX IDX_AD8A54A99395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, state_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, code VARCHAR(45) NOT NULL, phone VARCHAR(45) DEFAULT NULL, web VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, zip VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_81398E0977153098 (code), INDEX IDX_81398E095D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE audit (id INT AUTO_INCREMENT NOT NULL, windmill_id INT DEFAULT NULL, begin_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, status INT NOT NULL, type VARCHAR(255) DEFAULT NULL, tools TEXT DEFAULT NULL, observations TEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_9218FF7920C49033 (windmill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE audits_users (audit_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B5783646BD29F359 (audit_id), INDEX IDX_B5783646A76ED395 (user_id), PRIMARY KEY(audit_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE audit_windmill_blade (id INT AUTO_INCREMENT NOT NULL, audit_id INT DEFAULT NULL, windmill_blade_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_E239CFCBD29F359 (audit_id), INDEX IDX_E239CFC772F8851 (windmill_blade_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blade (id INT AUTO_INCREMENT NOT NULL, length INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, model VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_217C01E8D79572D9 (model), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blade_damage (id INT AUTO_INCREMENT NOT NULL, damage_id INT DEFAULT NULL, damage_category_id INT DEFAULT NULL, audit_windmill_blade_id INT DEFAULT NULL, position INT NOT NULL, radius INT NOT NULL, distance INT NOT NULL, size INT NOT NULL, status INT NOT NULL, number INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_37444DAF6CE425B7 (damage_id), INDEX IDX_37444DAF7917B854 (damage_category_id), INDEX IDX_37444DAFA50CFBEE (audit_windmill_blade_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE damage (id INT AUTO_INCREMENT NOT NULL, section INT NOT NULL, code VARCHAR(45) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, description VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_11C8546C77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE damage_category (id INT AUTO_INCREMENT NOT NULL, category INT NOT NULL, priority VARCHAR(255) NOT NULL, recommended_action VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_CDEABF3F5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, blade_damage_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, gps_longitude DOUBLE PRECISION DEFAULT NULL, gps_latitude DOUBLE PRECISION DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, INDEX IDX_14B7841880036B9B (blade_damage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, country_code VARCHAR(2) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE turbine (id INT AUTO_INCREMENT NOT NULL, tower_height INT NOT NULL, rotor_diameter INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, model VARCHAR(255) NOT NULL, power DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_4D391842D79572D9 (model), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE windfarm (id INT AUTO_INCREMENT NOT NULL, state_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, manager_id INT DEFAULT NULL, year INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(255) DEFAULT NULL, gps_longitude DOUBLE PRECISION DEFAULT NULL, gps_latitude DOUBLE PRECISION DEFAULT NULL, power DOUBLE PRECISION DEFAULT NULL, INDEX IDX_FA87FE285D83CC1 (state_id), INDEX IDX_FA87FE289395C3F3 (customer_id), INDEX IDX_FA87FE28783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE windmill (id INT AUTO_INCREMENT NOT NULL, windfarm_id INT DEFAULT NULL, turbine_id INT DEFAULT NULL, blade_type_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, gps_longitude DOUBLE PRECISION DEFAULT NULL, gps_latitude DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_80D327D877153098 (code), INDEX IDX_80D327D8A9FC0822 (windfarm_id), INDEX IDX_80D327D8C8418C82 (turbine_id), INDEX IDX_80D327D8C7079380 (blade_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE windmill_blade (id INT AUTO_INCREMENT NOT NULL, windmill_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_F31C8DD577153098 (code), INDEX IDX_F31C8DD520C49033 (windmill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acl_classes (id INT UNSIGNED AUTO_INCREMENT NOT NULL, class_type VARCHAR(200) NOT NULL, UNIQUE INDEX UNIQ_69DD750638A36066 (class_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acl_security_identities (id INT UNSIGNED AUTO_INCREMENT NOT NULL, identifier VARCHAR(200) NOT NULL, username TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8835EE78772E836AF85E0677 (identifier, username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acl_object_identities (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_object_identity_id INT UNSIGNED DEFAULT NULL, class_id INT UNSIGNED NOT NULL, object_identifier VARCHAR(100) NOT NULL, entries_inheriting TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9407E5494B12AD6EA000B10 (object_identifier, class_id), INDEX IDX_9407E54977FA751A (parent_object_identity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acl_object_identity_ancestors (object_identity_id INT UNSIGNED NOT NULL, ancestor_id INT UNSIGNED NOT NULL, INDEX IDX_825DE2993D9AB4A6 (object_identity_id), INDEX IDX_825DE299C671CEA1 (ancestor_id), PRIMARY KEY(object_identity_id, ancestor_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acl_entries (id INT UNSIGNED AUTO_INCREMENT NOT NULL, class_id INT UNSIGNED NOT NULL, object_identity_id INT UNSIGNED DEFAULT NULL, security_identity_id INT UNSIGNED NOT NULL, field_name VARCHAR(50) DEFAULT NULL, ace_order SMALLINT UNSIGNED NOT NULL, mask INT NOT NULL, granting TINYINT(1) NOT NULL, granting_strategy VARCHAR(30) NOT NULL, audit_success TINYINT(1) NOT NULL, audit_failure TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4 (class_id, object_identity_id, field_name, ace_order), INDEX IDX_46C8B806EA000B103D9AB4A6DF9183C9 (class_id, object_identity_id, security_identity_id), INDEX IDX_46C8B806EA000B10 (class_id), INDEX IDX_46C8B8063D9AB4A6 (object_identity_id), INDEX IDX_46C8B806DF9183C9 (security_identity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_user ADD CONSTRAINT FK_AD8A54A99395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E095D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE audit ADD CONSTRAINT FK_9218FF7920C49033 FOREIGN KEY (windmill_id) REFERENCES windmill (id)');
        $this->addSql('ALTER TABLE audits_users ADD CONSTRAINT FK_B5783646BD29F359 FOREIGN KEY (audit_id) REFERENCES audit (id)');
        $this->addSql('ALTER TABLE audits_users ADD CONSTRAINT FK_B5783646A76ED395 FOREIGN KEY (user_id) REFERENCES admin_user (id)');
        $this->addSql('ALTER TABLE audit_windmill_blade ADD CONSTRAINT FK_E239CFCBD29F359 FOREIGN KEY (audit_id) REFERENCES audit (id)');
        $this->addSql('ALTER TABLE audit_windmill_blade ADD CONSTRAINT FK_E239CFC772F8851 FOREIGN KEY (windmill_blade_id) REFERENCES windmill_blade (id)');
        $this->addSql('ALTER TABLE blade_damage ADD CONSTRAINT FK_37444DAF6CE425B7 FOREIGN KEY (damage_id) REFERENCES damage (id)');
        $this->addSql('ALTER TABLE blade_damage ADD CONSTRAINT FK_37444DAF7917B854 FOREIGN KEY (damage_category_id) REFERENCES damage_category (id)');
        $this->addSql('ALTER TABLE blade_damage ADD CONSTRAINT FK_37444DAFA50CFBEE FOREIGN KEY (audit_windmill_blade_id) REFERENCES audit_windmill_blade (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B7841880036B9B FOREIGN KEY (blade_damage_id) REFERENCES blade_damage (id)');
        $this->addSql('ALTER TABLE windfarm ADD CONSTRAINT FK_FA87FE285D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE windfarm ADD CONSTRAINT FK_FA87FE289395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE windfarm ADD CONSTRAINT FK_FA87FE28783E3463 FOREIGN KEY (manager_id) REFERENCES admin_user (id)');
        $this->addSql('ALTER TABLE windmill ADD CONSTRAINT FK_80D327D8A9FC0822 FOREIGN KEY (windfarm_id) REFERENCES windfarm (id)');
        $this->addSql('ALTER TABLE windmill ADD CONSTRAINT FK_80D327D8C8418C82 FOREIGN KEY (turbine_id) REFERENCES turbine (id)');
        $this->addSql('ALTER TABLE windmill ADD CONSTRAINT FK_80D327D8C7079380 FOREIGN KEY (blade_type_id) REFERENCES blade (id)');
        $this->addSql('ALTER TABLE windmill_blade ADD CONSTRAINT FK_F31C8DD520C49033 FOREIGN KEY (windmill_id) REFERENCES windmill (id)');
        $this->addSql('ALTER TABLE acl_object_identities ADD CONSTRAINT FK_9407E54977FA751A FOREIGN KEY (parent_object_identity_id) REFERENCES acl_object_identities (id)');
        $this->addSql('ALTER TABLE acl_object_identity_ancestors ADD CONSTRAINT FK_825DE2993D9AB4A6 FOREIGN KEY (object_identity_id) REFERENCES acl_object_identities (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE acl_object_identity_ancestors ADD CONSTRAINT FK_825DE299C671CEA1 FOREIGN KEY (ancestor_id) REFERENCES acl_object_identities (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE acl_entries ADD CONSTRAINT FK_46C8B806EA000B10 FOREIGN KEY (class_id) REFERENCES acl_classes (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE acl_entries ADD CONSTRAINT FK_46C8B8063D9AB4A6 FOREIGN KEY (object_identity_id) REFERENCES acl_object_identities (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE acl_entries ADD CONSTRAINT FK_46C8B806DF9183C9 FOREIGN KEY (security_identity_id) REFERENCES acl_security_identities (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE audits_users DROP FOREIGN KEY FK_B5783646A76ED395');
        $this->addSql('ALTER TABLE windfarm DROP FOREIGN KEY FK_FA87FE28783E3463');
        $this->addSql('ALTER TABLE admin_user DROP FOREIGN KEY FK_AD8A54A99395C3F3');
        $this->addSql('ALTER TABLE windfarm DROP FOREIGN KEY FK_FA87FE289395C3F3');
        $this->addSql('ALTER TABLE audits_users DROP FOREIGN KEY FK_B5783646BD29F359');
        $this->addSql('ALTER TABLE audit_windmill_blade DROP FOREIGN KEY FK_E239CFCBD29F359');
        $this->addSql('ALTER TABLE blade_damage DROP FOREIGN KEY FK_37444DAFA50CFBEE');
        $this->addSql('ALTER TABLE windmill DROP FOREIGN KEY FK_80D327D8C7079380');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B7841880036B9B');
        $this->addSql('ALTER TABLE blade_damage DROP FOREIGN KEY FK_37444DAF6CE425B7');
        $this->addSql('ALTER TABLE blade_damage DROP FOREIGN KEY FK_37444DAF7917B854');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E095D83CC1');
        $this->addSql('ALTER TABLE windfarm DROP FOREIGN KEY FK_FA87FE285D83CC1');
        $this->addSql('ALTER TABLE windmill DROP FOREIGN KEY FK_80D327D8C8418C82');
        $this->addSql('ALTER TABLE windmill DROP FOREIGN KEY FK_80D327D8A9FC0822');
        $this->addSql('ALTER TABLE audit DROP FOREIGN KEY FK_9218FF7920C49033');
        $this->addSql('ALTER TABLE windmill_blade DROP FOREIGN KEY FK_F31C8DD520C49033');
        $this->addSql('ALTER TABLE audit_windmill_blade DROP FOREIGN KEY FK_E239CFC772F8851');
        $this->addSql('ALTER TABLE acl_entries DROP FOREIGN KEY FK_46C8B806EA000B10');
        $this->addSql('ALTER TABLE acl_entries DROP FOREIGN KEY FK_46C8B806DF9183C9');
        $this->addSql('ALTER TABLE acl_object_identities DROP FOREIGN KEY FK_9407E54977FA751A');
        $this->addSql('ALTER TABLE acl_object_identity_ancestors DROP FOREIGN KEY FK_825DE2993D9AB4A6');
        $this->addSql('ALTER TABLE acl_object_identity_ancestors DROP FOREIGN KEY FK_825DE299C671CEA1');
        $this->addSql('ALTER TABLE acl_entries DROP FOREIGN KEY FK_46C8B8063D9AB4A6');
        $this->addSql('DROP TABLE admin_user');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE audit');
        $this->addSql('DROP TABLE audits_users');
        $this->addSql('DROP TABLE audit_windmill_blade');
        $this->addSql('DROP TABLE blade');
        $this->addSql('DROP TABLE blade_damage');
        $this->addSql('DROP TABLE damage');
        $this->addSql('DROP TABLE damage_category');
        $this->addSql('DROP TABLE admin_group');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE turbine');
        $this->addSql('DROP TABLE windfarm');
        $this->addSql('DROP TABLE windmill');
        $this->addSql('DROP TABLE windmill_blade');
        $this->addSql('DROP TABLE acl_classes');
        $this->addSql('DROP TABLE acl_security_identities');
        $this->addSql('DROP TABLE acl_object_identities');
        $this->addSql('DROP TABLE acl_object_identity_ancestors');
        $this->addSql('DROP TABLE acl_entries');
    }
}
