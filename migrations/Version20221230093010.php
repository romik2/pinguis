<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230093010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tool_type ADD command VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE tool_type SET command = "app:ping-tool-by-id" WHERE id = 1');
        $this->addSql('UPDATE tool_type SET command = "app:ping-tool-port-by-id" WHERE id = 2');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tool_type DROP command');
    }
}
