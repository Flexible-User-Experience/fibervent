<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190827105731 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE work_order (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, windfarm_id INT DEFAULT NULL, audit_id INT DEFAULT NULL, project_number VARCHAR(45) NOT NULL, is_from_audit TINYINT(1) NOT NULL, certifying_company_name VARCHAR(100) NOT NULL, certifying_company_contact_person VARCHAR(100) NOT NULL, certifying_company_phone VARCHAR(100) NOT NULL, certifying_company_email VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, removed_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_DDD2E8B78134F41E (project_number), INDEX IDX_DDD2E8B79395C3F3 (customer_id), INDEX IDX_DDD2E8B7A9FC0822 (windfarm_id), INDEX IDX_DDD2E8B7BD29F359 (audit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_order ADD CONSTRAINT FK_DDD2E8B79395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE work_order ADD CONSTRAINT FK_DDD2E8B7A9FC0822 FOREIGN KEY (windfarm_id) REFERENCES windfarm (id)');
        $this->addSql('ALTER TABLE work_order ADD CONSTRAINT FK_DDD2E8B7BD29F359 FOREIGN KEY (audit_id) REFERENCES audit (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD8A54A9A0D96FBF ON admin_user (email_canonical)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE work_order');
        $this->addSql('DROP INDEX UNIQ_AD8A54A9A0D96FBF ON admin_user');
    }
}
