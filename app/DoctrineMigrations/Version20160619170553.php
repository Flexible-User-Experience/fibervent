<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160619170553 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blade_damage ADD edge INT DEFAULT 0 NOT NULL');
        $this->addSql('DROP INDEX UNIQ_80D327D877153098 ON windmill');
        $this->addSql('CREATE UNIQUE INDEX windfarm_code_unique ON windmill (windfarm_id, code)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blade_damage DROP edge');
        $this->addSql('DROP INDEX windfarm_code_unique ON windmill');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_80D327D877153098 ON windmill (code)');
    }
}
