<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220828182102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tool_status DROP FOREIGN KEY FK_1E516900881ECFA7');
        $this->addSql('ALTER TABLE tool_status DROP FOREIGN KEY FK_1E5169009E91440F');
        $this->addSql('DROP INDEX IDX_1E516900881ECFA7 ON tool_status');
        $this->addSql('DROP INDEX IDX_1E5169009E91440F ON tool_status');
        $this->addSql('ALTER TABLE tool_status ADD tool_id INT DEFAULT NULL, ADD status_id INT DEFAULT NULL, DROP tool_id_id, DROP status_id_id');
        $this->addSql('ALTER TABLE tool_status ADD CONSTRAINT FK_1E5169008F7B22CC FOREIGN KEY (tool_id) REFERENCES tool (id)');
        $this->addSql('ALTER TABLE tool_status ADD CONSTRAINT FK_1E5169006BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_1E5169008F7B22CC ON tool_status (tool_id)');
        $this->addSql('CREATE INDEX IDX_1E5169006BF700BD ON tool_status (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tool_status DROP FOREIGN KEY FK_1E5169008F7B22CC');
        $this->addSql('ALTER TABLE tool_status DROP FOREIGN KEY FK_1E5169006BF700BD');
        $this->addSql('DROP INDEX IDX_1E5169008F7B22CC ON tool_status');
        $this->addSql('DROP INDEX IDX_1E5169006BF700BD ON tool_status');
        $this->addSql('ALTER TABLE tool_status ADD tool_id_id INT DEFAULT NULL, ADD status_id_id INT DEFAULT NULL, DROP tool_id, DROP status_id');
        $this->addSql('ALTER TABLE tool_status ADD CONSTRAINT FK_1E516900881ECFA7 FOREIGN KEY (status_id_id) REFERENCES status (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tool_status ADD CONSTRAINT FK_1E5169009E91440F FOREIGN KEY (tool_id_id) REFERENCES tool (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1E516900881ECFA7 ON tool_status (status_id_id)');
        $this->addSql('CREATE INDEX IDX_1E5169009E91440F ON tool_status (tool_id_id)');
    }
}
