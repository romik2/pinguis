<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221013161947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tool_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tool ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tool ADD CONSTRAINT FK_20F33ED1C54C8C93 FOREIGN KEY (type_id) REFERENCES tool_type (id)');
        $this->addSql('CREATE INDEX IDX_20F33ED1C54C8C93 ON tool (type_id)');
        
        $this->addSql('INSERT INTO tool_type (name, type) VALUES ("Проверка доступности с помощью ping", "command_ping")');
        $this->addSql('INSERT INTO tool_type (name, type) VALUES ("Проверка доступности по портам", "ping_port")');

        $this->addSql('UPDATE tool SET type_id = 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tool DROP FOREIGN KEY FK_20F33ED1C54C8C93');
        $this->addSql('DROP TABLE tool_type');
        $this->addSql('DROP INDEX IDX_20F33ED1C54C8C93 ON tool');
        $this->addSql('ALTER TABLE tool DROP type_id');
    }
}
