<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20191125102310 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_order_task ADD CONSTRAINT FK_4EC4CE120C49033 FOREIGN KEY (windmill_id) REFERENCES windmill (id)');
        $this->addSql('ALTER TABLE work_order DROP FOREIGN KEY FK_DDD2E8B7BD29F359');
        $this->addSql('DROP INDEX UNIQ_DDD2E8B78134F41E ON work_order');
        $this->addSql('DROP INDEX IDX_DDD2E8B7BD29F359 ON work_order');
        $this->addSql('ALTER TABLE work_order DROP audit_id');
        $this->addSql('ALTER TABLE presence_monitoring ADD category INT NOT NULL');
        $this->addSql('ALTER TABLE audit ADD workorder_id INT DEFAULT NULL, ADD has_work_order TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE audit ADD CONSTRAINT FK_9218FF792C1C3467 FOREIGN KEY (workorder_id) REFERENCES work_order (id)');
        $this->addSql('CREATE INDEX IDX_9218FF792C1C3467 ON audit (workorder_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE audit DROP FOREIGN KEY FK_9218FF792C1C3467');
        $this->addSql('DROP INDEX IDX_9218FF792C1C3467 ON audit');
        $this->addSql('ALTER TABLE audit DROP workorder_id, DROP has_work_order');
        $this->addSql('ALTER TABLE presence_monitoring DROP category');
        $this->addSql('ALTER TABLE work_order ADD audit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE work_order ADD CONSTRAINT FK_DDD2E8B7BD29F359 FOREIGN KEY (audit_id) REFERENCES audit (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DDD2E8B78134F41E ON work_order (project_number)');
        $this->addSql('CREATE INDEX IDX_DDD2E8B7BD29F359 ON work_order (audit_id)');
        $this->addSql('ALTER TABLE work_order_task DROP FOREIGN KEY FK_4EC4CE120C49033');
    }
}
