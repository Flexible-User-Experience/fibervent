<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20191030170939 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE work_order_task_delivery_note (work_order_task_id INT NOT NULL, delivery_note_id INT NOT NULL, INDEX IDX_F9A1E6A63B11D1F9 (work_order_task_id), INDEX IDX_F9A1E6A62CF3B78B (delivery_note_id), PRIMARY KEY(work_order_task_id, delivery_note_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_order_task_delivery_note ADD CONSTRAINT FK_F9A1E6A63B11D1F9 FOREIGN KEY (work_order_task_id) REFERENCES work_order_task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_order_task_delivery_note ADD CONSTRAINT FK_F9A1E6A62CF3B78B FOREIGN KEY (delivery_note_id) REFERENCES delivery_note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_order_task ADD CONSTRAINT FK_4EC4CE120C49033 FOREIGN KEY (windmill_id) REFERENCES windmill (id)');
        $this->addSql('ALTER TABLE presence_monitoring ADD category INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE work_order_task_delivery_note');
        $this->addSql('ALTER TABLE presence_monitoring DROP category');
        $this->addSql('ALTER TABLE work_order_task DROP FOREIGN KEY FK_4EC4CE120C49033');
    }
}
