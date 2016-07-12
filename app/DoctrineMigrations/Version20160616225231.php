<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160616225231 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE audit ADD windfarm_id INT DEFAULT NULL, ADD customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE audit ADD CONSTRAINT FK_9218FF79A9FC0822 FOREIGN KEY (windfarm_id) REFERENCES windfarm (id)');
        $this->addSql('ALTER TABLE audit ADD CONSTRAINT FK_9218FF799395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_9218FF79A9FC0822 ON audit (windfarm_id)');
        $this->addSql('CREATE INDEX IDX_9218FF799395C3F3 ON audit (customer_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE audit DROP FOREIGN KEY FK_9218FF79A9FC0822');
        $this->addSql('ALTER TABLE audit DROP FOREIGN KEY FK_9218FF799395C3F3');
        $this->addSql('DROP INDEX IDX_9218FF79A9FC0822 ON audit');
        $this->addSql('DROP INDEX IDX_9218FF799395C3F3 ON audit');
        $this->addSql('ALTER TABLE audit DROP windfarm_id, DROP customer_id');
    }
}
